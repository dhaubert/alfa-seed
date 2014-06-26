<?php

class Calculo {

    private $coef_reflexao = 0.23; //coeficiente de reflexão da vegetação
    private $as = 0.25;
    private $bs = 0.5;
    private $const_sb = 4.903E-9; //coeficiente de Stefan-Boltzmann 4,903 x 10^-9 - em MJm^-2/d
    private $Tbase = 10;
    private $Tupper = 32;

    function calcula_medias($estacao_id, $data_inicial, $data_final = NULL) {
        $estacoes = new Estacoes();
        $medias = $estacoes->busca_medias_diarias($estacao_id, $data_inicial, $data_final);
        return $medias;
    }

    function calcula_resultados($medias, $cultura) {
        $GDD = 0;
        for ($i = 0; $i < count($medias); $i++) {
            $medias[$i] = $this->ETo($medias[$i]);
            $medias[$i] = $this->graus_dia_acumulados($medias[$i], $GDD);
            $medias[$i] = $this->ETc($medias[$i], $cultura);
            $GDD = $medias[$i]['GD_acumulado'];
        }
        return $medias;
    }

    // Média aritmética
    function media($valores) {
        $soma = 0;
        foreach ($valores as $valor) {
            $soma += $valor;
        }
        return $soma / count($valores);
    }

    //e°(T) saturation vapour pressure at the air temperature T 
    function pressao_vapor($Tmax, $Tmin) {
        //saturation vapour pressure at the max temperature T
        $EoT_max = 0.6108 * exp((17.27 * $Tmax) / ($Tmax + 237.3)); //equação 11
        //saturation vapour pressure at the min temperature T
        $EoT_min = 0.6108 * exp((17.27 * $Tmin) / ($Tmin + 237.3)); //equação 11
        // (Es) Pressão de vapor de saturação - medido em kPa
        return array($this->media(array($EoT_max, $EoT_min)), $EoT_max, $EoT_min); //equação 12
    }

    //Atmospheric pressure (P) - equação 7
    function pressao_atmosferica($altitude) {
        return 101.3 * pow((293 - 0.0065 * $altitude) / 293, 5.26);
    }

    //Psychrometric constant (g) - equação 8
    function constante_psicometrica($pressao) {
        return 0.665E-3 * $pressao;
    }

    // Slope of saturation vapour pressure curve (D ) - equação 13
    function declividade_curva_vapor($Es, $T) {
        return ( 4098 * $Es ) / pow($T + 237.3, 2);
    }

    // Actual vapour pressure (ea) derived from relative humidity data - equação 17
    function pressao_vapor_real($Es_max, $Es_min, $UR_max, $UR_min) {
        $Ea_max = ($Es_min * $UR_max) / 100;
        $Ea_min = ($Es_max * $UR_min) / 100;
        return $this->media(array($Ea_max, $Ea_min));
    }

    //Extraterrestrial radiation for daily periods (Ra) 
    function radiacao_terrestre($J, $latitude_radianos) {
        // (Dr) Distância relativa inversa entre a Terra e o Sol - em radianos
        $Dr = 1 + 0.033 * cos((2 * M_PI / 365) * $J); //equação 23
        // (Ds) Ângulo de declinação solar  - em radianos         
        $Ds = 0.4093 * Sin((2 * M_PI / 365) * $J - 1.39); //equação 24
        // (Ws) Ângulo feito pelo Sol na Terra no horário do pôr-do-sol - em radianos 
        $Ws = acos(-tan($latitude_radianos) * tan($Ds)); //equação 25
        // (Ra) Radiação solar no topo da atmosfera
        $Ra = (24 * 60 / M_PI) * 0.0820 * $Dr * ($Ws * sin($latitude_radianos) * sin($Ds) + cos($latitude_radianos) * cos($Ds) * sin($Ws)); //equação 21

        return array($Ra, $Ws);
    }

    // Solar radiation (Rs) - equação 35
    function radiacao_solar($Ra, $insolacao, $Ws) {
        $N = 24 * $Ws / M_PI; //Daylight hours (N) - equação 34
        return (($this->as + $this->bs) * ($N / $insolacao) ) * $Ra; //equação 35
    }

    //Clear-sky solar radiation (Rso) - equação 37
    function radiacao_ceu_limpo($altitude, $Ra) {
        return (0.75 + 2E-5 * $altitude) * $Ra;
    }

    //Net solar or net shortwave radiation (Rns)  - equação 38
    function radiacao_ondas_curtas($Rs) {
        return (1 - $this->coef_reflexao) * $Rs;
    }

    //Net longwave radiation (Rnl)  - equação 39
    function radiacao_ondas_longas($Tmax, $Tmin, $Ea, $Rs, $Rso) {
        $TmaxK = $Tmax + 273.16;
        $TminK = $Tmin + 273.16;
        return $this->const_sb * ((pow($TmaxK, 4) + pow($TminK, 4)) / 2) * (0.34 - 0.14 * sqrt($Ea)) * (1.35 * ($Rs / $Rso) - 0.35);
    }

    function ETo($medias) {

//        $medias['estacao_id'] = 1010;
//        $medias['estacao'] = 'E - Luziânia';
//        $medias['latitude'] = -0.28389525616646666666667;
//        $medias['altitude'] = 958.00;
//        $medias['data'] = '2014-06-10';
//        $medias['hora'] = '00:00';
//        $medias['mes'] = 06 / 2014;
//        $medias['dia'] = 10;
//        $medias['registros'] = 24;
//        $medias['datas'] = 1;
//        $medias['temperatura_ar'] = 20.62500;
//        $medias['tipo_reg'] = h;
//        $medias['temperatura_minima'] = 15.8;
//        $medias['temperatura_maxima'] = 26.2;
//        $medias['insolacao'] = 11;
//        $medias['umidade_minima'] = 37;
//        $medias['umidade_maxima'] = 75;
//        $medias['umidade'] = 58.3750;
//        $medias['pressao'] = 90.90515627700;
//        $medias['vento'] = 3.065417;
//        $medias['precipitacao'] = 0.00;
//        $medias['radiacao'] = 439.193636;
//        $medias['radiacao_soma'] = 4831.13;
//        $medias['radiacao_soma_mj'] = 17.3920680;
//        
//        
        // Evapotranspiração de Referência, segundo Modelo Penman-Monteith-FAO
        // Disponível em: http://www.fao.org/3/a-x0490e/x0490e06.htm 
        // Acessado em 01/06/2014
        // Número de ordem do dia do ano (1 a 366)
        $J = date("z", strtotime($medias['data'])) + 1;
        //umidade relativa do ar minima do dia
        $UR_min = $medias['umidade_minima'];
        //umidade relativa do ar minima do dia
        $UR_max = $medias['umidade_maxima'];
        //vento a 2 metros de altura - em m/s
        $U2 = $medias['vento'];
        //temperatura máxima registrada no dia - em Celcius
        $Tmax = $medias['temperatura_maxima'] ? $medias['temperatura_maxima'] : $medias['temperatura_ar'];
        //temperatura mínima registrada no dia - em Celcius
        $Tmin = $medias['temperatura_minima'] ? $medias['temperatura_minima'] : $medias['temperatura_ar'];
        //média da temperatura máxima e mínima do dia - em Celcius
        $T = $this->media(array($Tmax, $Tmin));
        // altitude - em graus
        $altitude = $medias['altitude'];
        //pressão (se não há, calcula com base na altitude)- em hPa
        $P = $medias['pressao'] ? $medias['pressao'] : $this->pressao_atmosferica($altitude);
        //latitude - em graus
        $latitude = $medias['latitude'];
        //latitude - em radianos
        $latitude_radianos = ($latitude * M_PI) / 180; //equação 22
        //soma da radiação diária - kJ/m^2
        $radiacao_soma = $medias['radiacao_soma'];
        // insolacao, numero de horas de luz do sol real
        $insolacao = $medias['insolacao'];
        // (Es) Pressão de vapor de saturação - medido em kPa
        list($Es, $Es_max, $Es_min) = $this->pressao_vapor($Tmax, $Tmin);
        // (Delta) Declividade da curva de pressão de vapor em relação à temperatura - em kPa/Celcius 
        $D = $this->declividade_curva_vapor($Es, $T);
        // (G) Constante psicométrica - em kPa 
        $G = $this->constante_psicometrica($P);
        // (Ea) Pressão real de vapor - em kPa
        $Ea = $this->pressao_vapor_real($Es_max, $Es_min, $UR_max, $UR_min);
        // ($DeltaE) Déficit de pressão de vapor (saturação - pressao de vapor real)
        $DeltaE = $Es - $Ea;
        // (Ra) Radiação extraterrestre 
        list($Ra, $Ws) = $this->radiacao_terrestre($J, $latitude_radianos);
        // (Rs) Radiação solar medida
        $Rs = $radiacao_soma * 0.0036; //conversao para MJ
        if ($Rs > 21.39) { //em caso de omissão de radiacao da estação ou medição incorreta
            // (Rs) Radiação solar calculada
            $Rs = $this->radiacao_solar($Ra, $insolacao, $Ws);
        }
        // (Rso) Radiação solar incidente na ausência de nuvens -em MJ/m²/d
        $Rso = $this->radiacao_ceu_limpo($altitude, $Ra);
        // (Rns) Radiação solar de ondas curtas - em MJ/m²/d
        $Rns = $this->radiacao_ondas_curtas($Rs);
        // (Rnl) Radiação solar de ondas longas - em MJ/m²/d
        $Rnl = $this->radiacao_ondas_longas($Tmax, $Tmin, $Ea, $Rs, $Rso);
        // (Rn) Saldo de radiação
        $Rn = $Rns - $Rnl;
        $U2_ = (1 + 0.34 * $U2);
        $D_ = $D / ($D + $G * (1 + 0.34 * $U2_));
        $G_ = $G / ($D + $G * (1 + 0.34 * $U2_));
        $eto = 0.408 * $Rn * $D_ + $G_ * $DeltaE * $U2 * 900 / ($T + 273);
//        echo "D: $D | Ea: $Ea | Es: $Es | G: $G | Ra : $Ra | Rn: $Rn | Rs: $Rs | Rso : $Rso | Rnl: $Rnl | N: $N | N_n: $n_N | DeltaE: $DeltaE | DJ: $J  ";
        $medias['eto'] = $eto;
        return $medias;
    }

    function graus_dia_acumulados($media, $GD_acumulado) {
        $Tmax = $media['temperatura_maxima'];
        $Tmin = $media['temperatura_minima'];
        $Tavg = $this->media(array($Tmax, $Tmin));
        if ($Tavg > $this->Tupper) {
            $Tavg = $this->Tupper;
        } else {
            if ($Tavg < $this->Tbase) {
                $Tavg = $this->Tbase;
            }
        }
        $media['GD_diario'] = $Tavg - $this->Tbase;
        $media['GD_acumulado'] = $GD_acumulado + $media['GD_diario'];
        return $media;
    }

    function ETc($medias, $cultura) {
//        echo "<pre>->";
//        print_r($cultura);
//        echo "</pre>";
        $ETo = $medias['eto'];
        $GDD = $medias['GD_acumulado'];
        $Kc = $cultura['kc_ini'];
        //construcao da curva do coeficiente de cultura
        $medias['estagio'] = 'inicial';
        if ($GDD >= $cultura['gda_ini']) { //from sowing to emergence
//            $Kc = $cultura['kc_ini_mid'];//calcular elevacao da curva
            $medias['estagio'] = 'development';
        }
        if ($GDD >= $cultura['gda_dev']) { //flowering
//            $Kc = $cultura['kc_mid'];
            $medias['estagio'] = 'mid-season';
        }
        if ($GDD >= $cultura['gda_mid']) {
//            $Kc = $cultura['kc_mid_end'];//calcular declinacao da curva
            $medias['estagio'] = 'late-season';
        }
        if ($GDD >= $cultura['gda_late']) {
//            $Kc = $cultura['kc_end'];//alteracao
            $medias['estagio'] = 'maduro';
        }

        //evapotranspiração da cultura - equação 58
        $ETc = $Kc * $ETo;
        $medias['etc'] = $ETc;
        return $medias;
    }
    function Calcula_KC_Mid_End($kc_tab, $altura, $media_vento, $RHmin) {
      $kc = $kc_tab + 0.04 * ($media_vento - 2) - 0.004 * ($RHmin - 45) * pow(($altura / 3), 0.3);
      return $kc;
   }

   function Calcula_KC_Proporcional($gda, $gda_ini, $gda_dev, $gda_mid, $gda_late, $kc_ini, $kc_mid, $kc_end) {
      $GDDev = $gda_dev - $gda_ini;
      $GDLate = $gda_late - $gda_mid;

      if ($gda < $gda_dev) {
         $kc = $kc_ini + ($gda - $gda_ini) / $GDDev * ($kc_mid - $kc_ini);
      } else {
         if ($gda < $gda_mid) {
            $kc = $kc_mid;
         } else {

            /* @var $gda_late type */
            if ($gda < $gda_late) {
               $kc = $kc_mid + ($gda - $gda_mid) / $GDLate * ($kc_end - $kc_mid);
            } else {
               $kc = $kc_end;
            }
         }
      }
      return $kc;
   }

}
?>

