<?php

class Calculo {

    private $coef_reflexao = 0.23; //coeficiente de reflexão da vegetação (albedo)
    private $as = 0.25;
    private $bs = 0.5;
    private $const_sb = 4.903E-9; //coeficiente de Stefan-Boltzmann 4,903 x 10^-9 - em MJm^-2/d

    function calcula_medias($estacao_id, $data_inicial, $data_final = NULL) {
        $estacoes = new Estacoes();
        $medias = $estacoes->busca_medias_diarias($estacao_id, $data_inicial, $data_final);
        return $medias;
    }
    function calcula_eto($medias){
        for ($i = 0; $i < count($medias); $i++) {
            $medias[$i] = $this->ETo($medias[$i]);
        }
        return $medias;
    }
    function calcula_resultados($medias, $cultura, $solo) {
        $GDD = 0;
        for ($i = 0; $i < count($medias); $i++) {
            $medias[$i] = $this->ETo($medias[$i]);
            $medias[$i] = $this->graus_dia_acumulados($medias[$i], $GDD, $cultura);
            if($i == 0){
                $medias[0]['GD_acumulado'] = 0;
                $medias[0]['GD_diario'] = 0;
            }
            $medias[$i] = $this->ETc($medias[$i], $cultura, $solo);
            $GDD = $medias[$i]['GD_acumulado'];
            $Ks = $this->Ks($cultura, $solo, $media);
            $Etr = $this->ETr($ETc, $Ks);
            $medias[$i]['Etr'] = $ETr; 
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
        $Es = $this->media(array($EoT_max, $EoT_min));
        return array($Es, $EoT_max, $EoT_min); //equação 12
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
        $Rs = (($this->as + $this->bs) * ($N / $insolacao) ) * $Ra; //equação 35
        return $Rs;
    }

    //Clear-sky solar radiation (Rso) - equação 37
    function radiacao_ceu_limpo($altitude, $Ra) {
        $Rso = (0.75 + 2E-5 * $altitude) * $Ra;
        return $Rso;
    }

    //Net solar or net shortwave radiation (Rns)  - equação 38
    function radiacao_ondas_curtas($Rs) {
        $Rns = (1 - $this->coef_reflexao) * $Rs;
        return $Rns;
    }

    //Net longwave radiation (Rnl)  - equação 39
    function radiacao_ondas_longas($Tmax, $Tmin, $Ea, $Rs, $Rso) {
        $TmaxK = $Tmax + 273.16;
        $TminK = $Tmin + 273.16;
        $Rnl = $this->const_sb * ((pow($TmaxK, 4) + pow($TminK, 4)) / 2) * (0.34 - 0.14 * sqrt($Ea)) * (1.35 * ($Rs / $Rso) - 0.35);
        return $Rnl;
    }

    function ETo($medias) {

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
        if ($Rs > 21.39 || $Rs < 0) { //em caso de omissão de radiacao da estação ou medição incorreta
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

    function graus_dia_acumulados($media, $GD_acumulado, $cultura) {
        $Tbase = $cultura['temperatura_base'];
        $Tupper = $cultura['temperatura_superior'];
        $Tmax = $media['temperatura_maxima'];
        $Tmin = $media['temperatura_minima'];
        $Tavg = $this->media(array($Tmax, $Tmin));
        if ($Tavg > $Tupper) {
            $Tavg = $Tupper;
        } else {
            if ($Tavg < $Tbase) {
                $Tavg = $Tbase;
            }
        }
        $media['GD_diario'] = $Tavg - $Tbase;
        $media['GD_acumulado'] = $GD_acumulado + $media['GD_diario'];
        return $media;
    }

    function ETc($medias, $cultura, $solo) {
        $ETo = $medias['eto'];
        $GDD = $medias['GD_acumulado'];
        $Kc = $this->Kc_linear($cultura, $GDD);
        //construcao da curva do coeficiente de cultura
        $medias['estagio'] = 'inicial';
        if ($GDD >= $cultura['gda_ini']) { 
            $medias['estagio'] = 'development';
        }
        if ($GDD >= $cultura['gda_dev']) { 
            $medias['estagio'] = 'mid-season';
        }
        if ($GDD >= $cultura['gda_mid']) {
            $medias['estagio'] = 'late-season';
        }
        if ($GDD >= $cultura['gda_late']) {
            $medias['estagio'] = 'mature';
        }
//        
        $medias['prof_raiz'] = $this->profundidade_raiz($cultura, $medias['estagio'], $Kc);
//        $medias['CAD'] = $this->agua_disponivel_solo($solo, $medias['prof_raiz']);
        //evapotranspiração da cultura - equação 58
        $ETc = $Kc * $ETo;
        $medias['etc'] = $ETc;
        $medias['irrigacao'] = $ETc - $medias['chuva_efetiva'];
        return $medias;
    }
    
    function Kc_linear($cultura, $GD_acumulado) {
        $GD_ini_dev = $cultura['gda_dev'] - $cultura['gda_ini'];
        $GD_mid_late = $cultura['gda_late'] - $cultura['gda_mid'];
        if ($GD_acumulado <= $cultura['gda_ini']) { //inicial
            return $cultura['kc_ini'];
        } else {
            if ($GD_acumulado < $cultura['gda_dev'] ) { //crescimento
                return $cultura['kc_ini'] + ($GD_acumulado - $cultura['gda_ini']) / $GD_ini_dev * ($cultura['kc_mid'] - $cultura['kc_ini']);
            } else {
                if ($GD_acumulado <= $cultura['gda_mid']) { //intermediario
//                    return $this->Kc_mid_ajustado($media, $cultura);
                    return $cultura['kc_mid'];
                } else {
                    if ($GD_acumulado < $cultura['gda_late']) { //final
                        return $cultura['kc_mid'] + ($GD_acumulado - $cultura['gda_mid']) / $GD_mid_late * ($cultura['kc_end'] - $cultura['kc_mid']);
                    } else {
                        return $cultura['kc_end'];
                    }
                }
            }
        }
        return $kc_ini;
    }
    function ETr($ETc, $Ks){
        return $ETc * $Ks;
    }
    /* Fator de estresse hídrico */
    function Ks($cultura, $solo, $media){
        $profundidade_raiz = $this->profundidade_raiz($cultura, $media['estagio'], $media['kc']);
        $TAW = $this->TAW($solo, $profundidade_raiz);
        $RAW = $this->RAW($cultura, $TAW);
        $Ks = ($TAW - $cultura['Dr']) / $TAW - $RAW;
        return $Ks;
    }
    function rendimento_real($cultura, $ETc, $ETr){
        $Ymax = $cultura['produtividade_maxima'];
        $Ky = $cultura['ky'];
        $Yreal = $Ymax *(1-$Ky*(1-$ETr/$ETc));
        return $Yreal;
    }
    /* TAW - total água disponível */
    function TAW($solo, $profundidade_raiz){
        return (1000*($solo['CC'] - $solo['PMP'])) * $profundidade_raiz;
    }
    /* RAW - Agua facilmente disponivel */
    function RAW($cultura, $TAW){
        return $cultura['p'] * $TAW;
    }
    function profundidade_raiz($cultura, $estagio, $kc){
        if($estagio == 'inicial'){
            return $cultura['prof_min_raiz'];
        }else{
            if($estagio == 'development'){
                return (($kc - $cultura['kc_ini']) / ($cultura['kc_mid'] - $cultura['kc_ini'])) * 
                (($cultura['prof_max_raiz'] - $cultura['prof_min_raiz']) + $cultura['prof_max_raiz']);
            }
            if($estagio == 'mid-season' || $estagio == 'late-season'){
                return $cultura['prof_max_raiz'];
            }
        }
        return $cultura['prof_max_raiz'];
    }
}
?>

