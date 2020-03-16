<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<WebOrders>
@if(!empty($generate_response->seller_id))
 <WebOrder>
    <DocDate><?php if(!empty($generate_response->id)) echo $generate_response->id; ?></DocDate>
    <Anag>
       <Name><?php if(!empty($generate_response->seller_detail->company_name)) echo $generate_response->seller_detail->company_name; ?></Name>
       <Address><?php if(!empty($generate_response->seller_detail->registered_office)) echo $generate_response->seller_detail->registered_office; ?></Address>
       <ZipCode><?php if(!empty($generate_response->seller_detail->postal_code)) echo $generate_response->seller_detail->postal_code; ?></ZipCode>
       <City></City>
       <Province></Province>
       <CountryCode>IT</CountryCode>
       <EmailAddress><?php if(!empty($generate_response->seller_detail->email)) echo $generate_response->seller_detail->email; ?></EmailAddress>
       <PhoneNumber><?php if(!empty($generate_response->seller_detail->mobile_number)) echo $generate_response->seller_detail->mobile_number; ?></PhoneNumber>
       <CellularNumber><?php if(!empty($generate_response->seller_detail->mobile_number)) echo $generate_response->seller_detail->mobile_number; ?></CellularNumber>
       <FaxNumber></FaxNumber>
       <VatNumber></VatNumber>
       <PersonalID><?php if(!empty($generate_response->id)) echo $generate_response->id; ?></PersonalID>
       <LanguageRFC>en-gb</LanguageRFC>
       <EInvoiceAddress>ABC1234</EInvoiceAddress>
     </Anag>
     <DestinationAddress>
       <Name1><?php if(!empty($generate_response->user_detail->f_name)) echo $generate_response->user_detail->f_name." ".$generate_response->user_detail->l_name; ?></Name1>
       <Address1><?php if(!empty($generate_response->shipping_address->address_1)) echo $generate_response->shipping_address->address_1; ?></Address1>
       <ZipCode><?php if(!empty($generate_response->shipping_address->zip_code)) echo $generate_response->shipping_address->zip_code; ?></ZipCode>
       <City></City>
       <Province></Province>
       <CountryCode></CountryCode>
     </DestinationAddress>
     <Payment>
       <Code><?php if(!empty($generate_response->transaction_id)) echo $generate_response->transaction_id; ?></Code>
       <Name><?php if(!empty($generate_response->transaction_id)) echo $payment_mode_status[$generate_response->payment_mode]; ?></Name>
     </Payment>
       @if(count($generate_response->tyres) > 0)
          @foreach($generate_response->tyres as $tyre)
            <WebOrderRow>
               <Product>
               <Code>@if(!empty($tyre['product_name']))  {{ $tyre['product_name'] }}@endif</Code>
               </Product>
               <Quantity>@if(!empty($tyre['product_quantity']))  {{ $tyre['product_quantity'] }}@endif</Quantity>
               <Price>@if(!empty($tyre['price']))  {{ $tyre['price'] }}@endif</Price>
               <PriceIncludingVAT>@if(!empty($tyre['total_price']))  {{ $tyre['total_price'] }}@endif</PriceIncludingVAT>
               <DiscountPercent>@if(!empty($tyre['discount']))  {{ $tyre['discount'] }}@endif</DiscountPercent>
               <CurrencyType>
               <Code>EUR</Code>
                  </CurrencyType>
               <Tax>
               <Code>IVA0001</Code>
               <PercentAmount>21.0</PercentAmount>
               </Tax>
               <OrderDetailRef>ABC-12331-00</OrderDetailRef>
            </WebOrderRow>
         @endforeach
         @foreach($generate_response->spare_parts as $spare_parts)
            <WebOrderRow>
               <Product>
               <Code>@if(!empty($spare_parts['product_name']))  {{ $spare_parts['product_name'] }}@endif</Code>
               </Product>
               <Quantity>@if(!empty($spare_parts['product_quantity']))  {{ $spare_parts['product_quantity'] }}@endif</Quantity>
               <Price>@if(!empty($spare_parts['price']))  {{ $spare_parts['price'] }}@endif</Price>
               <PriceIncludingVAT>@if(!empty($spare_parts['total_price']))  {{ $spare_parts['total_price'] }}@endif</PriceIncludingVAT>
               <DiscountPercent>@if(!empty($spare_parts['discount']))  {{ $spare_parts['discount'] }}@endif</DiscountPercent>
               <CurrencyType>
               <Code>EUR</Code>
                  </CurrencyType>
               <Tax>
               <Code>IVA0001</Code>
               <PercentAmount>21.0</PercentAmount>
               </Tax>
               <OrderDetailRef>ABC-12331-00</OrderDetailRef>
            </WebOrderRow>
         @endforeach
      @endif
      @if(count($generate_response->spare_parts) > 0)
       <WebOrderRow>
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
       </WebOrderRow>
      @endif
 </WebOrder> 
@endif
@if(!empty($generate_response->workshop_id)) 
<WebOrder>
    <DocDate><?php if(!empty($generate_response->id)) echo $generate_response->id; ?></DocDate>
    <Anag>
       <Name><?php if(!empty($generate_response->workshop_seller_detail->company_name)) echo $generate_response->workshop_seller_detail->company_name; ?></Name>
       <Address><?php if(!empty($generate_response->workshop_seller_detail->registered_office)) echo $generate_response->workshop_seller_detail->registered_office; ?></Address>
       <ZipCode><?php if(!empty($generate_response->workshop_seller_detail->postal_code)) echo $generate_response->workshop_seller_detail->postal_code; ?></ZipCode>
       <City></City>
       <Province></Province>
       <CountryCode>IT</CountryCode>
       <EmailAddress><?php if(!empty($generate_response->workshop_seller_detail->email)) echo $generate_response->workshop_seller_detail->email; ?></EmailAddress>
       <PhoneNumber><?php if(!empty($generate_response->workshop_seller_detail->mobile_number)) echo $generate_response->workshop_seller_detail->mobile_number; ?></PhoneNumber>
       <CellularNumber><?php if(!empty($generate_response->workshop_seller_detail->mobile_number)) echo $generate_response->workshop_seller_detail->mobile_number; ?></CellularNumber>
       <FaxNumber></FaxNumber>
       <VatNumber></VatNumber>
       <PersonalID><?php if(!empty($generate_response->id)) echo $generate_response->id; ?></PersonalID>
       <LanguageRFC>en-gb</LanguageRFC>
       <EInvoiceAddress>ABC1234</EInvoiceAddress>
     </Anag>
     <DestinationAddress>
       <Name1><?php if(!empty($generate_response->user_detail->f_name)) echo $generate_response->user_detail->f_name." ".$generate_response->user_detail->l_name; ?></Name1>
       <Address1><?php if(!empty($generate_response->shipping_address->address_1)) echo $generate_response->shipping_address->address_1; ?></Address1>
       <ZipCode><?php if(!empty($generate_response->shipping_address->zip_code)) echo $generate_response->shipping_address->zip_code; ?></ZipCode>
       <City></City>
       <Province></Province>
       <CountryCode></CountryCode>
     </DestinationAddress>
     <Payment>
       <Code><?php if(!empty($generate_response->transaction_id)) echo $generate_response->transaction_id; ?></Code>
       <Name><?php if(!empty($generate_response->transaction_id)) echo $payment_mode_status[$generate_response->payment_mode]; ?></Name>
     </Payment>
      @if(count($generate_response->services) > 0)
      @foreach($generate_response->services as $service)
       <WebOrderRow>
         <Product>
           <Code>@if(!empty($service['service_name'])){{ $service['service_name'] }} @endif</Code>
         </Product>
         <BookigDate>@if(!empty($service['booking_date'])){{ $service['booking_date'] }} @endif</BookigDate>
         <Quantity>1</Quantity>
         <Price>@if(!empty($service['price'])){{ $service['price'] }} @endif</Price>
         <PriceIncludingVAT>@if(!empty($service['total_price'])){{ $service['total_price'] }} @endif</PriceIncludingVAT>
         <DiscountPercent>@if(!empty($service['discount'])){{ $service['discount'] }} @endif</DiscountPercent>
         <CurrencyType>
           <Code>EUR</Code>
         </CurrencyType>
         <Tax>
           <Code>IVA0001</Code>
           <PercentAmount>21.0</PercentAmount>
         </Tax>
         <OrderDetailRef>ABC-12331-00</OrderDetailRef>
       </WebOrderRow>
      @endforeach 
      @endif
      @if(count($generate_response->tyre_assemble) > 0)
      @foreach($generate_response->tyre_assemble as $service)
       <WebOrderRow>
         <Product>
           <Code>@if(!empty($service['service_name'])){{ $service['service_name'] }} @endif</Code>
         </Product>
         <BookigDate>@if(!empty($service['booking_date'])){{ $service['booking_date'] }} @endif</BookigDate>
         <Quantity>1</Quantity>
         <Price>@if(!empty($service->price)){{ $service->price }} @endif</Price>
         <PriceIncludingVAT>@if(!empty($service['total_price'])){{ $service['total_price'] }} @endif</PriceIncludingVAT>
         <DiscountPercent>@if(!empty($service['discount'])){{ $service['discount'] }} @endif</DiscountPercent>
         <CurrencyType>
           <Code>EUR</Code>
         </CurrencyType>
         <Tax>
           <Code>IVA0001</Code>
           <PercentAmount>21.0</PercentAmount>
         </Tax>
         <OrderDetailRef>ABC-12331-00</OrderDetailRef>
       </WebOrderRow>
      @endforeach 
      @endif
      @if(count($generate_response->spare_parts_assemble) > 0)
      @foreach($generate_response->spare_parts_assemble as $service)
       <WebOrderRow>
         <Product>
           <Code>@if(!empty($service['service_name'])){{ $service['service_name'] }} @endif</Code>
         </Product>
         <BookigDate>@if(!empty($service['booking_date'])){{ $service['booking_date'] }} @endif</BookigDate>
         <Quantity>1</Quantity>
         <Price>@if(!empty($service['price'])){{ $service['price'] }} @endif</Price>
         <PriceIncludingVAT>@if(!empty($service->total_price)){{ $service['total_price'] }} @endif</PriceIncludingVAT>
         <DiscountPercent>@if(!empty($service['discount'])){{ $service['discount'] }} @endif</DiscountPercent>
         <CurrencyType>
           <Code>EUR</Code>
         </CurrencyType>
         <Tax>
           <Code>IVA0001</Code>
           <PercentAmount>21.0</PercentAmount>
         </Tax>
         <OrderDetailRef>ABC-12331-00</OrderDetailRef>
       </WebOrderRow>
      @endforeach 
      @endif
 </WebOrder> 
@endif
</WebOrders>
</urlset>