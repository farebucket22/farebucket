function format (flightSegment, fareBreakdown, generalInfo, layover, segmentInfo, selectedRowId) {
    var flightDetailsHtmlString = [];
    var baseFare = parseFloat(fareBreakdown.BaseFare);
    var taxes = parseFloat(fareBreakdown.AdditionalTxnFee)+parseFloat(fareBreakdown.AirTransFee)+parseFloat(fareBreakdown.Tax)+parseFloat(fareBreakdown.OtherCharges)+parseFloat(fareBreakdown.ServiceTax);
    var total = baseFare + taxes;
    var ticketType = (generalInfo.NonRefundable) ? "Non - Refundable" : "Refundable";
    if( typeof selectedRowId === 'undefined' ){
        return "<div></div>";
    }
    if( flightSegment.length === 1 ){
        flightDetailsHtmlString[0] = 
            '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'
                +'<div class="col-lg-24">'
                    +'<div class="row">'
                        +'<div class="col-lg-16">'
                            +'<div class="row">'
                                +'<div class="col-lg-6 std-lineheight left-text">'
                                    +'<span class="inline-h4">'+flightSegment[0].Origin.CityName+'</span> <span class="glyphicon glyphicon-arrow-right"></span> <span class="inline-h4">'+flightSegment[0].Destination.CityName+'</span>'
                                +'</div>'
                                +'<div class="col-lg-7 right-text"><h4 class="h4-cng">'
                                    +'Departure'
                                +'</h4></div>'
                                +'<div class="col-lg-4 center-align-text">'
                                +'</div>'
                                +'<div class="col-lg-7 left-text"><h4 class="h4-cng">'
                                    +'Arrival'
                                +'</h4></div>'
                            +'</div>'
                            +'<div class="row">'
                                +'<div class="col-lg-6 left-text">'+flightSegment[0].Airline.AirlineName+'</div>'
                                +'<div class="col-lg-7 right-text"><span class="ctCode">'+flightSegment[0].Origin.CityCode+'</span> '+segmentInfo[0].DepartureTime+' <div class="row sd-details-line-height">'+segmentInfo[0].OriginAirportInfo+'</div><div class="row">'+segmentInfo[0].DepartureDate+'</div></div>'
                                +'<div class="col-lg-4 center-align-text duration-bg-color"><div class="row"><span class="glyphicon glyphicon-time"></span></div><div class="row">'+generalInfo.Duration+'</div></div>'
                                +'<div class="col-lg-7 left-text">'+segmentInfo[0].ArrivalTime+' <span class="ctCode">'+flightSegment[0].Destination.CityCode+'</span><div class="row sd-details-line-height">'+segmentInfo[0].DestinationAirportInfo+'</div><div class="row">'+segmentInfo[0].ArrivalDate+'</div></div>'
                            +'</div>'
                        +'</div>'
                        +'<div class="col-lg-8 large-left-border">'
                            +'<div class="row">'                                
                                +'<div class="col-lg-24"><h4 class="h4-cng">Fare Breakdown</h4></div>'
                            +'</div>'
                            +'<div class="row">'                                
                                +'<div class="col-lg-12 left-text">Taxes</div>'
                                +'<div class="col-lg-12 right-text">'+'<i class="fa fa-inr"></i>&nbsp;'+taxes+'</div>'
                            +'</div>'
                            +'<div class="row">'
                                +'<div class="col-lg-12 left-text">Base Fare</div>'
                                +'<div class="col-lg-12 right-text">'+'<i class="fa fa-inr"></i>&nbsp;'+baseFare+'</div>'
                            +'</div>'
                            +'<div class="row">'
                                +'<div class="col-lg-12 left-text">Total Fare</div>'
                                +'<div class="col-lg-12 right-text">'+'<i class="fa fa-inr"></i>&nbsp;'+total+'</div>'
                            +'</div>'
                            +'<div class="row">'                                
                                +'<div class="col-lg-24"><h4 class="h4-cng">Airline refund policy</h4></div>'
                            +'</div>'
                            +'<div class="row">'
                                +'<div class="col-lg-12 left-text">Ticket Type</div>'
                                +'<div class="col-lg-12 right-text">'+ticketType+'</div>'
                            +'</div>'
                        +'</div>'   
                    +'</div>'
                +'</div>'
            +'</table>';
    }else{
        flightDetailsHtmlString[0] = 
            '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'
                +'<div class="col-lg-24">'
                    +'<div class="row">'
                        +'<div class="col-lg-16">'
                            +'<div class="row">'
                                +'<div class="col-lg-6 left-text std-lineheight">'
                                    +'<span class="inline-h4">'+flightSegment[0].Origin.CityName+'</span> <span class="glyphicon glyphicon-arrow-right"></span> <span class="inline-h4">'+flightSegment[0].Destination.CityName+'</span>'
                                +'</div>'
                                +'<div class="col-lg-7 right-text"><h4 class="h4-cng">'
                                    +'Departure'
                                +'</h4></div>'
                                +'<div class="col-lg-4 center-align-text">'
                                +'</div>'
                                +'<div class="col-lg-7 left-text"><h4 class="h4-cng">'
                                    +'Arrival'
                                +'</h4></div>'
                            +'</div>';
        for ( var i = 1 ; i <= flightSegment.length ; i++ ) {
            if( layover.length > 1 ){
                if( i === flightSegment.length ){
                    flightDetailsHtmlString[i] = 
                    '<div class="row">'
                        +'<div class="col-lg-6 left-text">'+flightSegment[i-1].Airline.AirlineName+'</div>'
                        +'<div class="col-lg-7 right-text"><div class="row">'+segmentInfo[i-1].DepartureTime+' <span class="ctCode">'+flightSegment[i-1].Origin.CityCode+'</span></div><div class="row sd-details-line-height">'+segmentInfo[i-1].OriginAirportInfo+'</div><div class="row">'+segmentInfo[i-1].DepartureDate+'</div></div>'
                        +'<div class="col-lg-4 center-align-text duration-bg-color"><div class="row"><span class="glyphicon glyphicon-time"></span></div><div class="row">'+generalInfo.Duration+'</div></div>'
                        +'<div class="col-lg-7 left-text"><div class="row"><span class="ctCode">'+flightSegment[i-1].Destination.CityCode+'</span> '+segmentInfo[i-1].ArrivalTime+'</div><div class="row sd-details-line-height">'+segmentInfo[i-1].DestinationAirportInfo+'</div><div class="row">'+segmentInfo[i-1].ArrivalDate+'</div></div>'
                    +'</div>'
                }else{
                    flightDetailsHtmlString[i] = 
                    '<div class="row">'
                        +'<div class="col-lg-6 left-text">'+flightSegment[i-1].Airline.AirlineName+'</div>'
                        +'<div class="col-lg-7 right-text"><div class="row">'+segmentInfo[i-1].DepartureTime+' <span class="ctCode">'+flightSegment[i-1].Origin.CityCode+'</span></div><div class="row sd-details-line-height">'+segmentInfo[i-1].OriginAirportInfo+'</div><div class="row">'+segmentInfo[i-1].DepartureDate+'</div></div>'
                        +'<div class="col-lg-4 center-align-text duration-bg-color"><div class="row"><span class="glyphicon glyphicon-time"></span></div><div class="row">'+generalInfo.Duration+'</div></div>'
                        +'<div class="col-lg-7 left-text"><div class="row"><span class="ctCode">'+flightSegment[i-1].Destination.CityCode+'</span> '+segmentInfo[i-1].ArrivalTime+'</div><div class="row sd-details-line-height">'+segmentInfo[i-1].DestinationAirportInfo+'</div><div class="row">'+segmentInfo[i-1].ArrivalDate+'</div></div>'
                        +'<div class="col-lg-12"><div class="sr_sptr"></div></div>'
                        +'<div class="col-lg-6"><div class="center-align-text"> Layover - '+layover[i-1]+'</div></div>'
                        +'<div class="col-lg-6"><div class="sr_sptr"></div></div>'
                    +'</div>'
                }
            }else{
                if( i === flightSegment.length ){
                    flightDetailsHtmlString[i] = 
                    '<div class="row">'
                        +'<div class="col-lg-6 left-text">'+flightSegment[i-1].Airline.AirlineName+'</div>'
                        +'<div class="col-lg-7 right-text"><div class="row">'+segmentInfo[i-1].DepartureTime+' <span class="ctCode">'+flightSegment[i-1].Origin.CityCode+'</span></div><div class="row sd-details-line-height">'+segmentInfo[i-1].OriginAirportInfo+'</div><div class="row">'+segmentInfo[i-1].DepartureDate+'</div></div>'
                        +'<div class="col-lg-4 center-align-text duration-bg-color"><div class="row"><span class="glyphicon glyphicon-time"></span></div><div class="row">'+generalInfo.Duration+'</div></div>'
                        +'<div class="col-lg-7 left-text"><div class="row"><span class="ctCode">'+flightSegment[i-1].Destination.CityCode+'</span> '+segmentInfo[i-1].ArrivalTime+'</div><div class="row sd-details-line-height">'+segmentInfo[i-1].DestinationAirportInfo+'</div><div class="row">'+segmentInfo[i-1].ArrivalDate+'</div></div>'
                    +'</div>'
                }else{
                    flightDetailsHtmlString[i] = 
                    '<div class="row">'
                        +'<div class="col-lg-6 left-text">'+flightSegment[i-1].Airline.AirlineName+'</div>'
                        +'<div class="col-lg-7 right-text"><div class="row">'+segmentInfo[i-1].DepartureTime+' <span class="ctCode">'+flightSegment[i-1].Origin.CityCode+'</span></div><div class="row sd-details-line-height">'+segmentInfo[i-1].OriginAirportInfo+'</div><div class="row">'+segmentInfo[i-1].DepartureDate+'</div></div>'
                        +'<div class="col-lg-4 center-align-text duration-bg-color"><div class="row"><span class="glyphicon glyphicon-time"></span></div><div class="row">'+generalInfo.Duration+'</div></div>'
                        +'<div class="col-lg-7 left-text"><div class="row"><span class="ctCode">'+flightSegment[i-1].Destination.CityCode+'</span> '+segmentInfo[i-1].ArrivalTime+'</div><div class="row sd-details-line-height">'+segmentInfo[i-1].DestinationAirportInfo+'</div><div class="row">'+segmentInfo[i-1].ArrivalDate+'</div></div>'
                        +'<div class="col-lg-12"><div class="sr_sptr"></div></div>'
                        +'<div class="col-lg-6"><div class="center-align-text"> Layover - '+layover[i-1]+'</div></div>'
                        +'<div class="col-lg-6"><div class="sr_sptr"></div></div>'
                    +'</div>'
                }
            }
        }
        flightDetailsHtmlString[i] = 
                '</div>'
                    +'<div class="col-lg-8 large-left-border">'
                        +'<div class="row">'                                
                            +'<div class="col-lg-24"><h4 class="h4-cng">Fare Breakdown</h4></div>'
                        +'</div>'
                        +'<div class="row">'                                
                            +'<div class="col-lg-12 left-text">Taxes</div>'
                            +'<div class="col-lg-12 right-text">'+'<i class="fa fa-inr"></i>&nbsp;'+taxes+'</div>'
                        +'</div>'
                        +'<div class="row">'
                            +'<div class="col-lg-12 left-text">Base Fare</div>'
                            +'<div class="col-lg-12 right-text">'+'<i class="fa fa-inr"></i>&nbsp;'+baseFare+'</div>'
                        +'</div>'
                        +'<div class="row">'
                            +'<div class="col-lg-12 left-text">Total Fare</div>'
                            +'<div class="col-lg-12 right-text">'+'<i class="fa fa-inr"></i>&nbsp;'+total+'</div>'
                        +'</div>'
                        +'<div class="row">'                                
                            +'<div class="col-lg-24"><h4 class="h4-cng">Airline refund policy</h4></div>'
                        +'</div>'
                        +'<div class="row">'
                            +'<div class="col-lg-12 left-text">Ticket Type</div>'
                            +'<div class="col-lg-12 right-text">'+ticketType+'</div>'
                        +'</div>'
                    +'</div>'   
                +'</div>'
            +'</div>'
        +'</table>';
    }
    var returnElement = '';
    for (var j = 0 ; j < flightDetailsHtmlString.length ; j++) {
        returnElement += flightDetailsHtmlString[j];
    };
    return returnElement;
}