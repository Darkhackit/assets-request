<x-mail::message>
     <p>Dear <span style="font: bold">{{$invoice->user->name}}</span> ,</p>
    Your request for {{collect($invoice->items)->implode("name",",")}} has been approved by the Asset Management Committee.

    Please take note of these very important steps as follows:
    1) Kindly obtain your PRINTED and SIGNED pick-up voucher from Sharon, which will enable you to pick-up your items.
    NB: The Pick up voucher is valid for 3 days (i.e. items must be picked up within 3 days after the voucher is received).

    2) When picking-up your items, kindly go along with a copy of your Ghana Card.

    3) After picking up your item, kindly upload INVOICE and DELIVERY NOTE to the Asset Request Portal to complete the process.
    NB: Failure to upload the Invoice would lead to the initial proforma invoice amount being charged to you and deducted at source.

    Best Regards
{{ config('app.name') }}
</x-mail::message>
