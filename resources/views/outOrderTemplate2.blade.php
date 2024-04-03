<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nota de salida #{{substr("000000", strlen(strval($order->id)) ) }}{{$order->id}}</title>
	<style>
		.invoice-box {
			max-width: 100%;
			margin: auto;
			padding: 0px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
			font-size: 16px;
			line-height: 24px;
			font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			color: #555;
		}

		.invoice-box table {
			width: 100%;
			line-height: inherit;
			text-align: left;
		}

		.invoice-box table td {
			padding: 15px 5px;
			vertical-align: top;
		}

		.invoice-box table tr td:nth-child(2) {
			text-align: right;
		}

		.invoice-box table tr.top table td {
			padding-bottom: 20px;
		}

		.invoice-box table tr.top table td.title {
			font-size: 35px;
			line-height: 45px;
			color: #333;
		}

		.invoice-box table tr.information table td {
			padding-bottom: 40px;
		}

		.invoice-box table tr.heading td {
			background: #eee;
			border-bottom: 1px solid #ddd;
			font-weight: bold;
		}

		.invoice-box table tr.details td {
			padding-bottom: 20px;
		}

		.invoice-box table tr.item td {
			border-bottom: 1px solid #eee;
		}

		.invoice-box table tr.item.last td {
			border-bottom: none;
		}

		.invoice-box table tr.total td:nth-child(2) {
			border-top: 2px solid #eee;
			font-weight: bold;
		}

		@media only screen and (max-width: 600px) {
			.invoice-box table tr.top table td {
				width: 100%;
				display: block;
				text-align: center;
			}

			.invoice-box table tr.information table td {
				width: 100%;
				display: block;
				text-align: center;
			}
		}

		/** RTL **/
		.invoice-box.rtl {
			direction: rtl;
			font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
		}

		.invoice-box.rtl table {
			text-align: right;
		}

		.invoice-box.rtl table tr td:nth-child(2) {
			text-align: left;
		}
    .text-center{
			text-align: center!important;
		}
		*{
				font-family: Verdana, Geneva, Tahoma, sans-serif;
		}
	</style>
</head>
</head>
<body>
<div class="invoice-box" style="padding: 0px; margin:0px;width:100%; position:relative">
		<table style="width:100%" cellpadding="0" cellspacing="0">
			<tr class="top" style="width:100%" >
				<td colspan="5">
					<table>
						<tr>
							<td class="title" style="width:54%!important">
								Orden de Salida
							</td>

							<td>
								<div class="margin-right:50px">
									<div style="padding-bottom: 5px; border-bottom: 1px solid grey;">Despacho #: <b>{{substr("000000", strlen(strval($order->id)) ) }}{{$order->id}}</b></div>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr class="information" style="width:100%">
				<td colspan="5">
					<table >
						<tr>
							<td>
								<div style="     display: flex;
								flex-direction: column; width:100%">
									<div style="margin-top: 5px;">
										Solicitante:
										<b style="margin-top: 4px;">
											{{$order->order->client->name}}
										</b>
									</div>
									<div style="margin-top: 5px;">
										Dirección:
										<b style="margin-top: 4px;">

											{{$order->order->other_address}}
										</b>
									</div>
									<div style="margin-top: 5px;">
										Trancker ID:
										<b style="margin-top: 4px;">
											#{{$order->order->trancker}}
										</b>
									</div>
								</div>
							</td>
							<td>
							<div class="margin-right:50px">
								<div style="margin-top: 5px;" >Orden asociada #: <b>{{substr("000000", strlen(strval($order->order->idateid)) ) }}{{$order->order->id}}</b></div >

								<div style="margin-top: 5px;">Fecha de pedido: <b>{{date("d/m/Y  ", strtotime($order->order->created_at))}}</b></div>
								<div style="margin-top: 5px;">Fecha de envio: <b>{{date("d/m/Y  ", strtotime($order->created_at))}}</b></div>
								</div>
							</td>

						</tr>
					</table>
				</td>
			</tr>


			<tr class="heading" style="width:100%; border-bottom:1.2px solid grey; padding:5px; border-left:1px solid grey ; border-right:1px solid grey">
				<td style=" border-top: 1px solid grey; border-left: 1px solid grey; width:10px;"  class="text-center">Item</td>
				<td style=" border-top: 1px solid grey; border-left: 1px solid grey;" class="text-center" >Producto</td>
				<td style=" border-top: 1px solid grey; border-left: 1px solid grey;" class="text-center" >Lote</td>
				<td style=" border-top: 1px solid grey; border-left: 1px solid grey;" class="text-center" >Cantidad enviada</td>
				<td style=" border-top: 1px solid grey; border-left: 1px solid grey;" class="text-center" >Fecha de vencimiento</td>
			</tr>
			@foreach ($order->products as $product)
			<tr class="item" style="width:100%; border-bottom:1.2px solid grey; padding:5px; border-left:1px solid grey ; border-right:1px solid grey">
				<td style=" border-top: 1px solid grey; border-left: 1px solid grey; width:10px;" class="text-center" >{{$loop->index + 1}}</td>
				<td style=" border-top: 1px solid grey; border-left: 1px solid grey; font-style: italic !important; " class="text-center">
					<div style="display: block;">
						<div>
							
							{{ $product->title }} 
						</div>
						@if($product->pivot->recipe_id)
						<div style="font-size: 12px; margin-top:5px; border-radius:50%; background:#f9ece4; color:#d17048; padding:5px; line-height:1;">
							{{recipeOfProduct($product->pivot->recipe_id, $order->recipes)}}
						</div>
						@endif
					</div>
				</td>
				<td style=" border-top: 1px solid grey; border-left: 1px solid grey; " class="text-center">{{ $product->lotes[0]->lote_code }}</td>
				<td style=" border-top: 1px solid grey; border-left: 1px solid grey; " class="text-center">{{ $product->pivot->quantity }}</td>
				<td style=" border-top: 1px solid grey; border-left: 1px solid grey; " class="text-center">{{ date("d/m/Y", strtotime($product->lotes[0]->due_date))  }}</td>

			</tr>
			@endforeach


		</table>
    <div>
      <div style="margin-top: 10px; padding-top:10px;border-top:1px solid black; position:absolute; bottom:0%; left:0; right:0;">
        <div style="width: 100%; display:flex; justify-content:space-between">
          <span style="margin-top: 0px;">Coocking 5G</span>
          <span style="margin-top: 0px; float:right">Pagina 1</span>

        </div>
      </div>
    </div>
	</div>  
</body>
</html>



