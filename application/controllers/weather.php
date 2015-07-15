<?php
require_once (APPPATH . 'controllers/forecastio.php');
class Weather extends MY_Controller{

	function index()
	{
        $data = $this->input->post(null,true);
        $api_key = "8801a30e8fb63e680c469d109c34cf27";
        $forecastIoAPIObj = new ForecastIO($api_key);

        if( $data['is_predictable_weather'] == 0 ){
            $result = $forecastIoAPIObj->getCurrentConditions($data['source_city_lat'], $data['source_city_long'], "si", "en");
            $weatherResponseSource = array(
                'temperature' => $result->getTemperature(),
                'apparentTemperature' => $result->getApparentTemperature(),
                'summary' => $result->getSummary(),
                'icon' => $result->getIcon(),
                'humidity' => $result->getHumidity(),
            );
            $result = $forecastIoAPIObj->getCurrentConditions($data['destination_city_lat'], $data['destination_city_long'], "si", "en");
            $weatherResponseDestination = array(
                'temperature' => $result->getTemperature(),
                'apparentTemperature' => $result->getApparentTemperature(),
                'summary' => $result->getSummary(),
                'icon' => $result->getIcon(),
                'humidity' => $result->getHumidity(),
            );
            $isHistoric = 1;
        }else{
            $today = date('c', strtotime('now'));
            $choosen_date = date('c', strtotime($data['choosen_date']));
            $today = date_create($today);
            $choosen_date = date_create($choosen_date);
            $dayObj = date_diff($today, $choosen_date);
            $days = $dayObj->d;

            if( isset($choosen_date_return) ){
                $choosen_date_return = date('c', strtotime($data['choosen_date_return']));
                $choosen_date_return = date_create($choosen_date_return);
                $dayReturnObj = date_diff($today, $choosen_date_return);
                $daysReturn = $dayReturnObj->d;
            }

            $result = $forecastIoAPIObj->getForecastWeek($data['source_city_lat'], $data['source_city_long'], "si", "en");
            $weatherResponseSource = array(
                'minTemperature' => $result[$days]->getMinTemperature(),
                'maxTemperature' => $result[$days]->getMaxTemperature(),
                'apparentTemperatureMin' => $result[$days]->getApparentTemperatureMin(),
                'apparentTemperatureMax' => $result[$days]->getApparentTemperatureMax(),
                'summary' => $result[$days]->getSummary(),
                'icon' => $result[$days]->getIcon(),
                'humidity' => $result[$days]->getHumidity(),
            );
            if( isset($choosen_date_return) ){
                $result = $forecastIoAPIObj->getForecastWeek($data['destination_city_lat'], $data['destination_city_long'], "si", "en");
                $weatherResponseDestination = array(
                    'minTemperature' => $result[$daysReturn]->getMinTemperature(),
                    'maxTemperature' => $result[$daysReturn]->getMaxTemperature(),
                    'apparentTemperatureMin' => $result[$daysReturn]->getApparentTemperatureMin(),
                    'apparentTemperatureMax' => $result[$daysReturn]->getApparentTemperatureMax(),
                    'summary' => $result[$daysReturn]->getSummary(),
                    'icon' => $result[$daysReturn]->getIcon(),
                    'humidity' => $result[$daysReturn]->getHumidity(),
                );
            }else{
                $result = $forecastIoAPIObj->getForecastWeek($data['destination_city_lat'], $data['destination_city_long'], "si", "en");
                $weatherResponseDestination = array(
                    'minTemperature' => $result[$days]->getMinTemperature(),
                    'maxTemperature' => $result[$days]->getMaxTemperature(),
                    'apparentTemperatureMin' => $result[$days]->getApparentTemperatureMin(),
                    'apparentTemperatureMax' => $result[$days]->getApparentTemperatureMax(),
                    'summary' => $result[$days]->getSummary(),
                    'icon' => $result[$days]->getIcon(),
                    'humidity' => $result[$days]->getHumidity(),
                );
            }
            $isHistoric = 0;
        }

        $weatherResponse = array(
            'weatherResponseSource' => $weatherResponseSource,
            'weatherResponseDestination' => $weatherResponseDestination,
            'isHistoric' => $isHistoric
        );

        echo json_encode($weatherResponse);
	}

    function single_city(){
        $data = $this->input->post(null,true);
        $api_key = "8801a30e8fb63e680c469d109c34cf27";
        $forecastIoAPIObj = new ForecastIO($api_key);

        if( $data['is_predictable_weather'] == 0 ){
            $result = $forecastIoAPIObj->getCurrentConditions($data['city_lat'], $data['city_long'], "si", "en");
            $weatherResponse = array(
                'temperature' => $result->getTemperature(),
                'apparentTemperature' => $result->getApparentTemperature(),
                'summary' => $result->getSummary(),
                'icon' => $result->getIcon(),
                'humidity' => $result->getHumidity(),
                'isHistoric' => 1,
            );
        }else{
            $today = date('c', strtotime('now'));
            $choosen_date = date('c', strtotime($data['choosen_date']));
            $today = date_create($today);
            $choosen_date = date_create($choosen_date);
            $dayObj = date_diff($today, $choosen_date);
            $days = $dayObj->d;

            $result = $forecastIoAPIObj->getForecastWeek($data['city_lat'], $data['city_long'], "si", "en");
            $weatherResponse = array(
                'minTemperature' => $result[$days]->getMinTemperature(),
                'maxTemperature' => $result[$days]->getMaxTemperature(),
                'apparentTemperatureMin' => $result[$days]->getApparentTemperatureMin(),
                'apparentTemperatureMax' => $result[$days]->getApparentTemperatureMax(),
                'summary' => $result[$days]->getSummary(),
                'icon' => $result[$days]->getIcon(),
                'humidity' => $result[$days]->getHumidity(),
                'isHistoric' => 0,
            );
        }
        echo json_encode($weatherResponse);
    }

}
?>