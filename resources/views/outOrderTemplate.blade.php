<!-- Latest compiled and minified CSS -->
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<meta charset="utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<!------ Include the above in your HEAD tag ---------->
<style>
  .invoice-title h2, .invoice-title h3 {
    display: inline-block;
}

.table > tbody > tr > .no-line {
    border-top: none;
}

.table > thead > tr > .no-line {
    border-bottom: none;
}

.table > tbody > tr > .thick-line {
    border-top: 2px solid;
}
address{
  margin-bottom: 10px;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
    		<div class="invoice-title">
    			<h2>Orden de salida</h2><h3 class="pull-right">Salida  #{{substr("000000", strlen(strval($order->id)) ) }}{{$order->id}}</h3>
    		</div>
    		<hr>
    		<div class="row">
    			<div class="col-xs-6">
            <address class="mb-0">
        			<strong>Solicitante:</strong><br>
    					{{$order->order->client->name}}
    				</address>
    				<address class="mt-0">
    				<strong>Dirección de destino:</strong><br>
            {{$order->order->other_address}}
    				</address>
    			</div>
    			<div class="col-xs-6 text-right">
    				<address>
        			<strong>Fecha de pedio:</strong><br>
    					{{date("D M j G:i:s ", strtotime($order->order->created_at))}}
    				</address>
            <address>
              <strong>Fecha de despacho:</strong><br>
              {{date("D M j G:i:s ", strtotime($order->created_at))}}
            </address>
            <address>
        			<strong>Tracker ID:</strong><br>
    					{{$order->order->trancker}}
    				</address>
    			</div>
    		</div>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
    			<div class="panel-heading">
    				<h3 class="panel-title"><strong>Productos</strong></h3>
    			</div>
    			<div class="panel-body">
    				<div class="table-responsive">
    					<table class="table table-condensed">
    						<thead>
                    <tr>
        							<td><strong>Item</strong></td>
        							<td class="text-center"><strong>Producto</strong></td>
        							<td class="text-center"><strong>Lote</strong></td>
        							<td class="text-right"><strong>Cantidad enviada</strong></td>
        							<td class="text-right"><strong>Fecha de vencimiento</strong></td>
                    </tr>
    						</thead>
    						<tbody>
                  @foreach ($order->products as $product)
                  <tr>
    								<td>{{$loop->index + 1}}</td>
    								<td class="text-center">{{$product->title}}</td>
    								<td class="text-center">{{$product->loteccc->id_lote}}</td>
                    <td class="text-center">{{$product->pivot->quantity }}</td>
                    <td class="text-center">1</td>

    							</tr>
                  @endforeach
    						</tbody>
    					</table>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
</div>