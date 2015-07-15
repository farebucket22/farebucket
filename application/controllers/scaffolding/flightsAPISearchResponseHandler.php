<?php
@session_start();

class FlightsAPISearchResponseHandler{

	private $durationCorrection;
	
	public function checkIsDomestic($isDomestic){
		if ($isDomestic == 1) {
			$_SESSION['IsDomestic'] = $isDomestic;
            return $isDomestic;
        }
        else {
			$_SESSION['IsDomestic'] = 0;
            return 0;
        }
	}

	public function checkIsOneWay($isOneWay, $sessionId){
		if ($isOneWay != 'OneWay') {
            if (isset($_SESSION['cnt_val'])){
            	$_SESSION['sess_id'][$_SESSION['cnt_val'] - 1] = $sessionId;
            }
        }
        else {
            $_SESSION['cnt_val'] = 1;
            $_SESSION['sess_id'][0] = $sessionId;
        }
	}

	public function checkIsReturn($isOneWay, $sessionId){
		if($isOneWay == "Return"){
	        $sessionId = explode(",", $sessionId);
	        $_SESSION['outbound_id'] = $sessionId[0];
        	$_SESSION['inbound_id'] = $sessionId[1];
		}
	}

	public function get_timezone_offset($remote_tz, $origin_tz = null) {
		if($origin_tz === null) {
			if(!is_string($origin_tz = date_default_timezone_get())) {
			return false;
		}
		}
		$origin_dtz = new DateTimeZone($origin_tz);
		$remote_dtz = new DateTimeZone($remote_tz);
		$origin_dt = new DateTime("now", $origin_dtz);
		$remote_dt = new DateTime("now", $remote_dtz);
		$offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
		return $offset;
	}

	public function getIndividualFlightResults($wsResult, $utf_from, $utf_to){
		$general = array();
		$segment = array();
		$fare = array();
		$fareRule = array();
		$i=0;
		$firstKeyOfArray = "".key($wsResult);
		$durationCorrection = 0;
		$this->durationCorrection = $this->get_timezone_offset($utf_to, $utf_from) / 3600;

		if( $firstKeyOfArray != "TripIndicator" ){
			foreach ($wsResult as $res) {
				if(is_object($res->Segment->WSSegment)){
					$segmentSizeArr[] = 0;
					$general[$i]['DepartureTime'] = date('H:i', strtotime($res->Segment->WSSegment->DepTIme));
					$general[$i]['ArrivalTime'] = date('H:i', strtotime($res->Segment->WSSegment->ArrTime));
					$general[$i]['DepartureDateTime'] = ($res->Segment->WSSegment->DepTIme);
					$general[$i]['ArrivalDateTime'] = ($res->Segment->WSSegment->ArrTime);
					$general[$i]['AirlineName'] = $res->Segment->WSSegment->Airline->AirlineName;
					$general[$i]['AirlineImage'] = $res->Segment->WSSegment->Airline->AirlineCode;
					$general[$i]['OriginCityName'] = $res->Segment->WSSegment->Origin->CityName;
					$general[$i]['DestinationCityName'] = $res->Segment->WSSegment->Destination->CityName;
					$general[$i]['StopsCount'] = 0;
					$segment[$i][0] = $res->Segment->WSSegment;
				} else{
					$segmentSize = sizeof($res->Segment->WSSegment);
					$segmentSizeArr[] = $segmentSize;
					$general[$i]['DepartureTime'] = date('H:i', strtotime($res->Segment->WSSegment[0]->DepTIme));
					$general[$i]['ArrivalTime'] = date('H:i', strtotime($res->Segment->WSSegment[$segmentSize-1]->ArrTime));
					$general[$i]['DepartureDateTime'] = ($res->Segment->WSSegment[0]->DepTIme);
					$general[$i]['ArrivalDateTime'] = ($res->Segment->WSSegment[$segmentSize-1]->ArrTime);
					$general[$i]['AirlineName'] = $res->Segment->WSSegment[0]->Airline->AirlineName;
					$general[$i]['AirlineImage'] = $res->Segment->WSSegment[0]->Airline->AirlineCode;
					$general[$i]['OriginCityName'] = $res->Segment->WSSegment[0]->Origin->CityName;
					$general[$i]['DestinationCityName'] = $res->Segment->WSSegment[$segmentSize-1]->Destination->CityName;
					$general[$i]['StopsCount'] = count($res->Segment->WSSegment);
					$segment[$i] = $res->Segment->WSSegment;
				}

				$segmentInfo[$i] = $this->getAdditionalSegmentInfo($segment[$i]);
				$layoverInfo[$i] = $this->getLayoverInfo($segment[$i]);
				
				$fare[] = $res->Fare;
				$fareRule[] = $res->FareRule;
				$fareBreakdown[] = $res->FareBreakdown;

				$general[$i]['TotalFare'] = $this->calculateTotalFare($res->Fare);
				$general[$i]['DepartureTimeMins'] = $this->calculateDepartureTimeInMins($general[$i]['DepartureTime']);
				$general[$i]['TripIndicator'] = $res->TripIndicator;
				$general[$i]['Origin'] = $res->Origin;
				$general[$i]['Destination'] = $res->Destination;
				$general[$i]['NonRefundable'] = $res->NonRefundable;
				$general[$i]['PromotionalPlanType'] = $res->PromotionalPlanType;
				$general[$i]['Source'] = $res->Source;
				$general[$i]['SegmentKey'] = $res->SegmentKey;
				$general[$i]['IsLCC'] = $res->IsLcc;
				$general[$i]['IbSegCount'] = $res->IbSegCount;
				$general[$i]['ObSegCount'] = $res->ObSegCount;
				$general[$i]['IsLCC'] = $res->IsLcc;
				$general[$i]['Duration'] = $this->calculateDuration($general[$i]['ArrivalDateTime'], $general[$i]['DepartureDateTime'], 1);
				$i += 1;
			}
		}else{
			$segmentSizeArr[] = 0;
			$general[$i]['DepartureTime'] = date('H:i', strtotime($wsResult['Segment']->WSSegment->DepTIme));
			$general[$i]['ArrivalTime'] = date('H:i', strtotime($wsResult['Segment']->WSSegment->ArrTime));
			$general[$i]['DepartureDateTime'] = ($wsResult['Segment']->WSSegment->DepTIme);
			$general[$i]['ArrivalDateTime'] = ($wsResult['Segment']->WSSegment->ArrTime);
			$general[$i]['AirlineName'] = $wsResult['Segment']->WSSegment->Airline->AirlineName;
			$general[$i]['AirlineImage'] = $wsResult['Segment']->WSSegment->Airline->AirlineCode;
			$general[$i]['OriginCityName'] = $wsResult['Segment']->WSSegment->Origin->CityName;
			$general[$i]['DestinationCityName'] = $wsResult['Segment']->WSSegment->Destination->CityName;
			$general[$i]['StopsCount'] = 0;
			$segment[$i][0] = $wsResult['Segment']->WSSegment;

			$segmentInfo[$i] = $this->getAdditionalSegmentInfo($segment[$i]);
			$layoverInfo[$i] = $this->getLayoverInfo($segment[$i]);
			
			$fare[] = $wsResult['Fare'];
			$fareRule[] = $wsResult['FareRule'];
			$fareBreakdown[] = $wsResult['FareBreakdown'];

			$general[$i]['TotalFare'] = $this->calculateTotalFare($wsResult['Fare']);
			$general[$i]['DepartureTimeMins'] = $this->calculateDepartureTimeInMins($general[$i]['DepartureTime']);
			$general[$i]['TripIndicator'] = $wsResult['TripIndicator'];
			$general[$i]['Origin'] = $wsResult['Origin'];
			$general[$i]['Destination'] = $wsResult['Destination'];
			$general[$i]['NonRefundable'] = $wsResult['NonRefundable'];
			$general[$i]['PromotionalPlanType'] = $wsResult['PromotionalPlanType'];
			$general[$i]['Source'] = $wsResult['Source'];
			$general[$i]['SegmentKey'] = $wsResult['SegmentKey'];
			$general[$i]['IsLCC'] = $wsResult['IsLcc'];
			$general[$i]['IbSegCount'] = $wsResult['IbSegCount'];
			$general[$i]['ObSegCount'] = $wsResult['ObSegCount'];
			$general[$i]['IsLCC'] = $wsResult['IsLcc'];
			$general[$i]['Duration'] = $this->calculateDuration($general[$i]['ArrivalDateTime'], $general[$i]['DepartureDateTime'], 1);
			$i += 1;
		}
			
		$filterValues = $this->calculateFilterValues($general);
		return array("general"=>$general, "segment"=>$segment, "fare"=>$fare, "fareRule"=> $fareRule, "fareBreakdown"=>$fareBreakdown, "filterValues"=>$filterValues, "segmentInfo"=>$segmentInfo, "layoverInfo"=>$layoverInfo);
	}

	public function calculateDuration($arrTime, $depTime, $correctionFlag = null){
		if( $correctionFlag ){
			$durationCorrectionHrs = (int)$this->durationCorrection;
			$durationCorrectionMins = ($this->durationCorrection - $durationCorrectionHrs) * 60;

			$hours = abs(floor((strtotime($arrTime) - strtotime($depTime))/3600));
			$minutes = abs(floor((strtotime($arrTime) - strtotime($depTime)-($hours*3600))/60));
			$minutes += $durationCorrectionMins;
			if( $minutes >= 60 ){
				$hours = $hours + $durationCorrectionHrs + 1;
				$minutes -= 60;
			}else{
				$hours = $hours + $durationCorrectionHrs;
			}
			$h = ( $hours < 10 ) ? "0".$hours : $hours;
			$m = ( $minutes < 10 ) ? "0".$minutes : $minutes;
		}else{
			$hours = abs(floor((strtotime($arrTime) - strtotime($depTime))/3600));
			$minutes = abs(floor((strtotime($arrTime) - strtotime($depTime)-($hours*3600))/60));
			$h = ( $hours < 10 ) ? "0".$hours : $hours;
			$m = ( $minutes < 10 ) ? "0".$minutes : $minutes;
		}
		$total_min = 0;
		$total_min += $h * 60;
	    $total_min += $m;

	    $day = floor ($total_min / 1440);
		if( $day > 0 ){
			$day = $day . "d ";
		}
		else{
			$day = "";
		}
	    $hr = floor (($total_min - $day * 1440) / 60)."h ";
	    $min = $total_min - ($day * 1440) - ($hr * 60)."m ";
		return $day . $hr. $min;
	}

	public function calculateDepartureTimeInMins($depTime){
		$depDate = date("Y-m-d", strtotime($depTime));
		$depDate .= "T00:00:00";
		return ((strtotime($depTime) - strtotime($depDate))/60);
	}

	public function calculateTotalFare($fareObj){
		$totalFare = $fareObj->AdditionalTxnFee + $fareObj->AirTransFee + $fareObj->BaseFare + $fareObj->Tax + $fareObj->OtherCharges + $fareObj->ServiceTax;
		return $totalFare;
	}

	public function calculateFilterValues($arr){
		$fares = array_map(function($details) {
			return $details['TotalFare'];
		}, $arr);

		$depTimesMins = array_map(function($details) {
			return $details['DepartureTimeMins'];
		}, $arr);

		$airlineNames = array_map(function($details) {
			return $details['AirlineName'];
		}, $arr);
		$tempArr = array_unique($airlineNames);
		foreach($tempArr as $tem) {
			if ($tem != '' || $tem != null) {
			$airlineNamesArr[] = $tem;
			}
		}
		sort($airlineNamesArr);

		$minStopsCount = 999;
		$maxStopsCount = 0;
		foreach($arr as $a){
			if( $a['StopsCount'] < $minStopsCount ){
				$minStopsCount = $a['StopsCount'];
			}
			if( $a['StopsCount'] > $maxStopsCount ){
				$maxStopsCount = $a['StopsCount'];
			}
		}

		$_SESSION['multiTravelData']['flightData'] = array(
			"flightType" => "single",
			"maxFare" => max($fares), 
			"minFare" => min($fares)
		);

		return array("MaxFare"=>max($fares), "MinFare"=>min($fares), "MaxDepTime"=>max($depTimesMins), "MinDepTime"=>min($depTimesMins), "MaxStops"=>$maxStopsCount, "MinStops"=>$minStopsCount, "AirlineNames"=>$airlineNamesArr);
	}

	public function getAdditionalSegmentInfo($curSegment){
		$i=0;
		foreach ($curSegment as $curSeg) {
			$curSegDepartureTime = date("H:i", strtotime($curSeg->DepTIme));
			$curSegDepartureDate = date("D, jS M Y", strtotime($curSeg->DepTIme));
			$curSegArrivalTime = date("H:i", strtotime($curSeg->ArrTime));
			$curSegArrivalDate = date("D, jS M Y", strtotime($curSeg->ArrTime));
			$curSegDepartureDateTime = $curSeg->DepTIme;
			$curSegArrivalDateTime = $curSeg->ArrTime;
			$curSegDuration = $this->calculateDuration($curSegArrivalDateTime, $curSegDepartureDateTime);

			if(isset($curSeg->Origin->Terminal) && !empty($curSeg->Origin->Terminal)){
				$curSegOriginTerminal = "Terminal ".$curSeg->Origin->Terminal.", ";
			} else{
				$curSegOriginTerminal = "";
			}
			if(isset($curSeg->Destination->Terminal) && !empty($curSeg->Destination->Terminal)){
				$curSegDestinationTerminal = "Terminal ".$curSeg->Destination->Terminal.", ";
			} else{
				$curSegDestinationTerminal = "";
			}

			if(isset($curSeg->Origin->AirportName) && !empty($curSeg->Origin->AirportName)){
				$curSegOriginAirportName = $curSeg->Origin->AirportName.", ";
			} else{
				$curSegOriginAirportName = "";
			}
			if(isset($curSeg->Destination->AirportName) && !empty($curSeg->Destination->AirportName)){
				$curSegDestinationAirportName = $curSeg->Destination->AirportName.", ";
			} else{
				$curSegDestinationAirportName = "";
			}

			$curSegOriginCityName = $curSeg->Origin->CityName;
			$curSegDestinationCityName = $curSeg->Destination->CityName;

			$curSegOriginAirportInfo = $curSegOriginTerminal.$curSegOriginAirportName.$curSegOriginCityName;
			$curSegDestinationAirportInfo = $curSegDestinationTerminal.$curSegDestinationAirportName.$curSegDestinationCityName;

			$segmentAdditionalInfo[$i] = array("DepartureTime"=>$curSegDepartureTime, "ArrivalTime"=>$curSegArrivalTime, "DepartureDate"=>$curSegDepartureDate, "ArrivalDate"=> $curSegArrivalDate, "OriginAirportInfo"=>$curSegOriginAirportInfo, "DestinationAirportInfo"=>$curSegDestinationAirportInfo, "SegmentDuration"=>$curSegDuration);
			$i += 1;			
		}

		return $segmentAdditionalInfo;
	}

	public function getLayoverInfo($curSegment){
		$i=0;
		$j=0;
		$layover = array();
		$segmentSize = sizeof($curSegment);
		foreach ($curSegment as $curSeg) {
			if($segmentSize != 1){
				if($i>0 && $i<=$segmentSize-1){
					$curSegDepTime = $curSeg->DepTIme;
					$layover[$j] = $this->calculateDuration($curSegDepTime, $prevSegArrTime);
					$j += 1;
					$prevSegArrTime = $curSeg->ArrTime;
				} else if($i==0){
					$prevSegArrTime = $curSeg->ArrTime;
				}
			}
			$i += 1;
		}

		return $layover;
	}
}

?>