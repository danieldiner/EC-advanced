var app = {

	initialize: function() {
		app.renderItemsCheckout();
	},

	renderItemsCheckout: function() {

		if (app.supportLocalStorage()) {
	        	
        	// Checks if cartArray already exists
        	if (localStorage.getItem("cartArray") != null) {

        		// cartArray already exists

        		// get cartArray from local storage 
        		var cartArrayString = localStorage.getItem("cartArray");

        		// parse cartArray so that we can manipulate it
        		var cartArray = JSON.parse(cartArrayString);

        		var total = 0;

        		var itemsArrayPaypal = [];

        		for (var i = 0; i < cartArray.length; i++) {

        			var subTotal = parseInt(cartArray[i].quantity) * parseInt(cartArray[i].price);
        			var total = total + subTotal;

        			var item = '<tr>' +
        						  '<td><img src="' + cartArray[i].imgUrl + '" height="80px"></td>' +		
					              '<td>' + cartArray[i].title + '</td>' +
					              '<td>' + cartArray[i].quantity + '</td>' +
					              '<td>$ ' + cartArray[i].price + '</td>' +
					              '<td>$ ' + subTotal + '</td>' +
					            '</tr>';

					var itemPaypal = {
                                      "name": cartArray[i].title,
                                      "price": cartArray[i].price,
                                      "currency": "MXN",
                                      "quantity": cartArray[i].quantity
                                    };

					$('#tbody-items').append(item);

					itemsArrayPaypal.push(itemPaypal);

        		}

        		var totalItem = '<tr>' +
        						  '<td></td>' +		
					              '<td></td>' +
					              '<td></td>' +
					              '<td><strong>TOTAL</strong></td>' +
					              '<td><strong>$ ' + total + '</strong></td>' +
					            '</tr>';

				$('#tbody-items').append(totalItem);

				app.renderPaypalButton(total,itemsArrayPaypal);

        	} else {

        		// cartArray does NOT exist yet

        		console.log("You don't have anything in the cart");
        	}

        } else {
        	alert("Ups! This browser does not support local storage. Please try with one of the following: Chrome,Firefox,Internet Explorer,Safari,Opera");
        }

	},

	renderPaypalButton: function(amountTotal,itemsArray) {

		var CREATE_PAYMENT_URL  = '../server_services/create-payment.php';
      	var EXECUTE_PAYMENT_URL = '../server_services/execute-payment.php';

		paypal.Button.render({

            env: 'sandbox', 

          	locale: 'es_MX',

	        style: {
	            label: 'pay',
	            size:  'large', 
	            shape: 'rect',   
	            color: 'silver'
	         },

          	client: {
             	sandbox: 'AdLP7TfHOHls5OU6jM-hxJtfJCJLF599FsAhkpCrkhKw5FOKNa1PrCJ8cbiyNurH97bM4T7Tf5OL5c_v'
          	},

          	commit: true, // Show a 'Pay Now' button

            payment: function(data, actions) {
            	
            	var transactionsArray = {
		            "amount":
		            { 
		              "total": amountTotal,
		              "currency":"MXN"
		            }, 
		            "item_list":
		            {
		              "items": itemsArray
		            },
		            "description":"Compra desde Geenius Store"
		          };

		        //Configurar los datos que se pasarán al servidor
		        var dataArray = {
		        	transactions: JSON.stringify(transactionsArray)
		        };
		          
		        return paypal.request.post(CREATE_PAYMENT_URL, dataArray).then(function(data) {
		            console.log("Success - paymentID: " + data.paymentID);
		            return data.paymentID;
		        },function(error) {
		            console.log("Error: " + error);
		        });

            },
              
            onAuthorize: function(data, actions) {
  				
  				console.dir(data);

	         	//Configurar los datos que se pasarán al servidor
		        var dataArray = {
		            paymentID: data.paymentID,
		            payerID: data.payerID
		        };
	          
	          	return paypal.request.post(EXECUTE_PAYMENT_URL, dataArray).then(function() {
	             	alert("¡Pago Completado! Gracias por su compra, vuelva pronto.")


			        localStorage.removeItem("cartArray");

			        window.location = "index.php";

	             	// El pago se ha completado
              		// Puede mostrar un mensaje de confirmación al comprador
		        },function(error) {
		            console.log("Error: " + error);
		        });

	         },

	        onCancel: function(data, actions) {
	            // Show a cancel page or return to cart
	            console.log("Error: " + data);
	        }

        }, '#paypal-button-container');

	},

	supportLocalStorage: function() {
		if(typeof(Storage) !== "undefined") {
			return true;
		} else {
			return false;
		}

	}

}

app.initialize();