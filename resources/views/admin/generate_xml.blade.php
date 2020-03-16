<?php
$xml_string = '';
$xml_string .= '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<WebOrders>';
if(!empty($generate_response->seller_id)){
$xml_string .= '<WebOrder>
                    <DocDate>'.$generate_response->id.'</DocDate>
                    <Anag>
                        <Name>'.$generate_response->seller_detail->company_name .'</Name>
                        <Address>'.$generate_response->seller_detail->registered_office.'</Address>
                        <ZipCode>'.$generate_response->seller_detail->postal_code.'</ZipCode>
                        <City></City>
                        <Province></Province>
                        <CountryCode>IT</CountryCode>
                        <EmailAddress>'.$generate_response->seller_detail->email .'</EmailAddress>
                        <PhoneNumber>'. $generate_response->seller_detail->mobile_number.'</PhoneNumber>
                        <CellularNumber>'.$generate_response->seller_detail->mobile_number.'</CellularNumber>
                        <FaxNumber></FaxNumber>
                        <VatNumber></VatNumber>
                        <PersonalID>'. $generate_response->id .'</PersonalID>
                        <LanguageRFC>en-gb</LanguageRFC>
                        <EInvoiceAddress>ABC1234</EInvoiceAddress>
                      </Anag>
                      <DestinationAddress>
                        <Name1>'.$generate_response->user_detail->f_name." ".$generate_response->user_detail->l_name .'</Name1>
                        <Address1>'.$generate_response->shipping_address->address_1 .'</Address1>
                        <ZipCode>'. $generate_response->shipping_address->zip_code.'</ZipCode>
                        <City></City>
                        <Province></Province>
                        <CountryCode></CountryCode>
                      </DestinationAddress>
                      <Payment>
                        <Code>'.$generate_response->transaction_id .'</Code>
                        <Name>online</Name>
                      </Payment>';

                         if(count($generate_response->tyres) > 0){
                            foreach($generate_response->tyres as $tyre){
                        $xml_string .= '<WebOrderRow>
                                          <Product>
                                          <Code>'.$tyre["product_name"].'</Code>
                                          </Product>
                                          <Quantity>'.$tyre["product_quantity"].'</Quantity>
                                          <Price>'.$tyre["price"].'</Price>
                                          <PriceIncludingVAT>'.$tyre["total_price"].'</PriceIncludingVAT>
                                          <DiscountPercent>'.$tyre["discount"].'</DiscountPercent>
                                          <CurrencyType>
                                          <Code>EUR</Code>
                                            </CurrencyType>
                                          <Tax>
                                          <Code>IVA0001</Code>
                                          <PercentAmount>21.0</PercentAmount>
                                          </Tax>
                                          <OrderDetailRef>ABC-12331-00</OrderDetailRef>
                                      </WebOrderRow>';
                            }
                          }      
                          if(count($generate_response->spare_parts) > 0){            
                             foreach($generate_response->spare_parts as $spare_parts){
                          $xml_string .= '<WebOrderRow>
                                                <Product>
                                                <Code>'.$spare_parts["product_name"].'</Code>
                                                </Product>
                                                <Quantity>'.$spare_parts["product_quantity"].'</Quantity>
                                                <Price>'.$spare_parts["price"].'</Price>
                                                <PriceIncludingVAT>'.$spare_parts["total_price"].'</PriceIncludingVAT>
                                                <DiscountPercent>'.$spare_parts["discount"].'</DiscountPercent>
                                                <CurrencyType>
                                                <Code>EUR</Code>
                                                  </CurrencyType>
                                                <Tax>
                                                <Code>IVA0001</Code>
                                                <PercentAmount>21.0</PercentAmount>
                                                </Tax>
                                                <OrderDetailRef>ABC-12331-00</OrderDetailRef>
                                            </WebOrderRow>';
                                }
                            }
                                     if(count($generate_response->spare_parts) > 0){
                                      $xml_string  .= '<WebOrderRow>
                                                         <Product>
                                                              <Code>ABCD1234</Code>
                                                            </Product>
                                                            <Quantity>2</Quantity>
                                                            <Price>123.10</Price>
                                                            <PriceIncludingVAT>150.00</PriceIncludingVAT>
                                                            <DiscountPercent>0</DiscountPercent>
                                                            <CurrencyType>
                                                              <Code>EUR</Code>
                                                            </CurrencyType>
                                                            <Tax>
                                                              <Code>IVA0001</Code>
                                                              <PercentAmount>21.0</PercentAmount>
                                                            </Tax>
                                                            <OrderDetailRef>ABC-12331-00</OrderDetailRef>
                                                          </WebOrderRow>';
                                        }
$xml_string .= '</WebOrder>'; 
 }
/*manage for workshop*/
if(!empty($generate_response->workshop_id)){ 
 $xml_string .= '<WebOrder>
      <DocDate> if(!empty($generate_response->id)) $generate_response->id; </DocDate>
      <Anag>
         <Name>'.$generate_response->workshop_seller_detail->company_name.'</Name>
         <Address>'.$generate_response->workshop_seller_detail->registered_office .'.</Address>
         <ZipCode>'. $generate_response->workshop_seller_detail->postal_code .'</ZipCode>
         <City></City>
         <Province></Province>
         <CountryCode>IT</CountryCode>
         <EmailAddress>'.$generate_response->workshop_seller_detail->email .'</EmailAddress>
         <PhoneNumber>'. $generate_response->workshop_seller_detail->mobile_number .'</PhoneNumber>
         <CellularNumber>'. $generate_response->workshop_seller_detail->mobile_number .'</CellularNumber>
         <FaxNumber></FaxNumber>
         <VatNumber></VatNumber>
         <PersonalID>'.$generate_response->id .'</PersonalID>
         <LanguageRFC>en-gb</LanguageRFC>
         <EInvoiceAddress>ABC1234</EInvoiceAddress>
       </Anag>
       <DestinationAddress>
         <Name1>'.$generate_response->user_detail->f_name." ".$generate_response->user_detail->l_name .'</Name1>
         <Address1>'. $generate_response->shipping_address->address_1 .'</Address1>
         <ZipCode>'.$generate_response->shipping_address->zip_code .'</ZipCode>
         <City></City>
         <Province></Province>
         <CountryCode></CountryCode>
       </DestinationAddress>
       <Payment>
         <Code> '.$generate_response->transaction_id .'</Code>
         <Name> '.$payment_mode_status[$generate_response->payment_mode] .'</Name>
       </Payment>';
        if(count($generate_response->services) > 0){
         foreach($generate_response->services as $service){
                $xml_string .= '<WebOrderRow>
                                  <Product>
                                    <Code>'.$service["service_name"].'</Code>
                                  </Product>
                                  <BookigDate>'.$service["booking_date"].'</BookigDate>
                                  <Quantity>1</Quantity>
                                  <Price> '.$service["price"] .'</Price>
                                  <PriceIncludingVAT>'.$service["total_price"] .'</PriceIncludingVAT>
                                  <DiscountPercent>'. $service["discount"] .'</DiscountPercent>
                                  <CurrencyType>
                                    <Code>EUR</Code>
                                  </CurrencyType>
                                  <Tax>
                                    <Code>IVA0001</Code>
                                    <PercentAmount>21.0</PercentAmount>
                                  </Tax>
                                  <OrderDetailRef>ABC-12331-00</OrderDetailRef>
                                </WebOrderRow>';
         }
        }
        if(count($generate_response->tyre_assemble) > 0){
         foreach($generate_response->tyre_assemble as $service){
                  $xml_string =   '<WebOrderRow>
                                        <Product>
                                          <Code>'.$service["service_name"] .'</Code>
                                        </Product>
                                        <BookigDate>'. $service["booking_date"] .'</BookigDate>
                                        <Quantity>1</Quantity>
                                        <Price>'.$service->price.'</Price>
                                        <PriceIncludingVAT>'.$service["total_price"].'</PriceIncludingVAT>
                                        <DiscountPercent>'.$service["discount"].'</DiscountPercent>
                                        <CurrencyType>
                                          <Code>EUR</Code>
                                        </CurrencyType>
                                        <Tax>
                                          <Code>IVA0001</Code>
                                          <PercentAmount>21.0</PercentAmount>
                                        </Tax>
                                        <OrderDetailRef>ABC-12331-00</OrderDetailRef>
                                      </WebOrderRow>';
          }
        }
        if(count($generate_response->spare_parts_assemble) > 0){
        foreach($generate_response->spare_parts_assemble as $service){
         $xml_string = '<WebOrderRow>
                          <Product>
                            <Code>'.$service["service_name"].'</Code>
                          </Product>
                          <BookigDate>'.$service["booking_date"].'</BookigDate>
                          <Quantity>1</Quantity>
                          <Price>'.$service["price"].'</Price>
                          <PriceIncludingVAT>'.$service["total_price"].'</PriceIncludingVAT>
                          <DiscountPercent>'.$service["discount"].'</DiscountPercent>
                          <CurrencyType>
                            <Code>EUR</Code>
                          </CurrencyType>
                          <Tax>
                            <Code>IVA0001</Code>
                            <PercentAmount>21.0</PercentAmount>
                          </Tax>
                          <OrderDetailRef>ABC-12331-00</OrderDetailRef>
                        </WebOrderRow>';
        } 
      }
 $xml_string .= '</WebOrder>'; 
}
/*End*/




$xml_string .= '</WebOrders>
                </urlset>';
$file = $generate_response->id;
// $myFile = url("public/storage/order_invoice/".$sitemap.".xml");
$myFile = public_path('order_invoice/'.$file.".xml");

//echo $myFile;exit;
$fh = fopen($myFile,'w');               
fwrite($fh, $xml_string);
if(fclose($fh)){
  echo 200;exit;
}               
else{
  echo 100;exit;
} 


/* $dom = new DOMDocument;
$dom->preserveWhiteSpace = FALSE;
$dom->loadXML($xml_string);
//Save XML as a file
$url = url('storage/order_invoice/sitemap.xml');
echo $url;exit;
$dom->save('xml/sitemap.xml'); */
