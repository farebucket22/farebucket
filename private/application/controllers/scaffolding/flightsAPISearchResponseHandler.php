<?php
@session_start();

class FlightsAPISearchResponseHandler{
	
	public function checkIsDomestic($isDomestic){
		if ($isDomestic == 1) {
            $_SESSION['IsDomestic'] = $isDomestic;
        }
        else {
            $_SESSION['IsDomestic'] = 0;
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

	public function getIndividualFlightResults($wsResult){
		//print_r($wsResult); die;
		$general = array();
		$segment = array();
		$fare = array();
		$fareRule = array();
		$i=0;
		foreach ($wsResult as $res) {
			if(!is_array($res->Segment->WSSegment)){
				$segmentSizeArr[] = 0;
				$general[$i]['DepartureTime'] = date('H:i', strtotime($res->Segment->WSSegment->DepTIme));
				$general[$i]['ArrivalTime'] = date('H:i', strtotime($res->Segment->WSSegment->ArrTime));
				$general[$i]['AirlineName'] = $res->Segment->WSSegment->Airline->AirlineName;
				$general[$i]['AirlineImage'] = $res->Segment->WSSegment->Airline->AirlineCode;
				$general[$i]['OriginCityName'] = $res->Segment->WSSegment->Origin->CityName;
				$general[$i]['DestinationCityName'] = $res->Segment->WSSegment->Destination->CityName;

				$segment[$i][0] = $res->Segment->WSSegment;
			} else{
				$segmentSize = sizeof($res->Segment->WSSegment);
				$segmentSizeArr[] = $segmentSize;
				$general[$i]['DepartureTime'] = date('H:i', strtotime($res->Segment->WSSegment[0]->DepTIme));
				$general[$i]['ArrivalTime'] = date('H:i', strtotime($res->Segment->WSSegment[$segmentSize-1]->ArrTime));
				$general[$i]['AirlineName'] = $res->Segment->WSSegment[0]->Airline->AirlineName;
				$general[$i]['AirlineImage'] = $res->Segment->WSSegment[0]->Airline->AirlineCode;
				$general[$i]['OriginCityName'] = $res->Segment->WSSegment[0]->Origin->CityName;
				$general[$i]['DestinationCityName'] = $res->Segment->WSSegment[$segmentSize-1]->Destination->CityName;

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
			$general[$i]['StopsCount'] = count($res->Segment->WSSegment);
			$general[$i]['SegmentKey'] = $res->SegmentKey;
			$general[$i]['IsLCC'] = $res->IsLcc;
			$general[$i]['IbSegCount'] = $res->IbSegCount;
			$general[$i]['ObSegCount'] = $res->ObSegCount;
			$general[$i]['IsLCC'] = $res->IsLcc;
			$general[$i]['Duration'] = $this->calculateDuration($general[$i]['ArrivalTime'], $general[$i]['DepartureTime']);
			$i += 1;
		}
		$filterValues = $this->calculateFilterValues($general, $segmentSizeArr);
		return array("general"=>$general, "segment"=>$segment, "fare"=>$fare, "fareRule"=> $fareRule, "fareBreakdown"=>$fareBreakdown, "filterValues"=>$filterValues, "segmentInfo"=>$segmentInfo, "layoverInfo"=>$layoverInfo);
	}

	public function calculateDuration($arrTime, $depTime){
		$hours = abs(floor((strtotime($arrTime) - strtotime($depTime))/3600));
		$minutes = abs(floor((strtotime($arrTime) - strtotime($depTime)-($hours*3600))/60));
		$h = ( $hours < 10 ) ? "0".$hours : $hours;
		$m = ( $minutes < 10 ) ? "0".$minutes : $minutes;
		return $h."h ". $m . "m";	
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

	public function calculateFilterValues($arr, $segmentSizeArr){
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

		return array("MaxFare"=>max($fares), "MinFare"=>min($fares), "MaxDepTime"=>max($depTimesMins), "MinDepTime"=>min($depTimesMins), "MaxStops"=>max($segmentSizeArr), "MinStops"=>min($segmentSizeArr), "AirlineNames"=>$airlineNamesArr);
	}

	public function getAdditionalSegmentInfo($curSegment){
		$i=0;
		foreach ($curSegment as $curSeg) {
			$curSegDepartureTime = date("H:i", strtotime($curSeg->DepTIme));
			$curSegDepartureDate = date("D, jS M Y", strtotime($curSeg->DepTIme));
			$curSegArrivalTime = date("H:i", strtotime($curSeg->ArrTime));
			$curSegArrivalDate = date("D, jS M Y", strtotime($curSeg->ArrTime));

			if($curSeg->Origin->Terminal != ""){
				$curSegOriginTerminal = "Terminal ".$curSeg->Origin->Terminal.", ";
			} else{
				$curSegOriginTerminal = "";
			}
			if($curSeg->Destination->Terminal != ""){
				$curSegDestinationTerminal = "Terminal ".$curSeg->Destination->Terminal.", ";
			} else{
				$curSegDestinationTerminal = "";
			}

			if($curSeg->Origin->AirportName != ""){
				$curSegOriginAirportName = $curSeg->Origin->AirportName.", ";
			} else{
				$curSegOriginAirportName = "";
			}
			if($curSeg->Destination->AirportName != ""){
				$curSegDestinationAirportName = $curSeg->Destination->AirportName.", ";
			} else{
				$curSegDestinationAirportName = "";
			}

			$curSegOriginCityName = $curSeg->Origin->CityName;
			$curSegDestinationCityName = $curSeg->Destination->CityName;

			$curSegOriginAirportInfo = $curSegOriginTerminal.$curSegOriginAirportName.$curSegOriginCityName;
			$curSegDestinationAirportInfo = $curSegDestinationTerminal.$curSegDestinationAirportName.$curSegDestinationCityName;

			$segmentAdditionalInfo[$i] = array("DepartureTime"=>$curSegDepartureTime, "ArrivalTime"=>$curSegArrivalTime, "DepartureDate"=>$curSegDepartureDate, "ArrivalDate"=> $curSegArrivalDate, "OriginAirportInfo"=>$curSegOriginAirportInfo, "DestinationAirportInfo"=>$curSegDestinationAirportInfo);
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