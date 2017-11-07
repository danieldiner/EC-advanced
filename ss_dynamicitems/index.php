<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geenius Store</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/jquery.bootstrap-touchspin.css" rel="stylesheet" type="text/css" />

    <script src="https://www.paypalobjects.com/api/checkout.js"></script>

  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Geenius Store</a>
        </div>
      </div>
    </nav>

    <div class="container">

      <div class="row" id="items-list">

      </div>


    </div><!-- /.container -->

    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.bootstrap-touchspin.js"></script>
    <script src="js/parse-1.6.14.min.js"></script>

    <script type="text/javascript">

      var CREATE_PAYMENT_URL  = '../server_services/create-payment.php';
      var EXECUTE_PAYMENT_URL = '../server_services/execute-payment.php';

      function renderPaypalButtons(buttonsPromises) {

          var itemsProcessed = 0;
          
          buttonsPromises.forEach(function(opts) {

              paypal.Button.render({

                  env: 'sandbox', 

                  locale: 'es_ES',

                  style: {
                      label: 'pay',
                      size:  'small', 
                      shape: 'rect',   
                      color: 'black'
                  },

                  client: {
                      sandbox: 'AdLP7TfHOHls5OU6jM-hxJtfJCJLF599FsAhkpCrkhKw5FOKNa1PrCJ8cbiyNurH97bM4T7Tf5OL5c_v'
                  },

                  commit: true, // Show a 'Pay Now' button

                  payment(data, actions) {

                    var numArticles = $(opts.productInputId).val();
                    var amountTotal = (parseInt(numArticles)*opts.productPrice).toString();

                    var transactionsArray = {
                      "amount":
                      { 
                        "total": amountTotal, 
                        "currency": "MXN"
                      }, 
                      "item_list":
                      {
                        "items": [
                          {
                                "name": opts.productName,
                                "description": opts.productDescription,
                                "quantity": numArticles,
                                "price": opts.productPrice,
                                "currency": "MXN"
                          }]
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
                  
                  onAuthorize(data, actions) {
                      
                      console.dir(data);

                      //Configurar los datos que se pasarán al servidor
                      var dataArray = {
                          paymentID: data.paymentID,
                          payerID: data.payerID
                      };
                      
                      return paypal.request.post(EXECUTE_PAYMENT_URL, dataArray).then(function() {
                          alert("¡Pago Completado! Gracias por su compra, vuelva pronto.")
                          // El pago se ha completado
                          // Puede mostrar un mensaje de confirmación al compradoromer
                      },function(error) {
                          console.log("Error: " + error);
                      });

                  },

                  onCancel: function(data, actions) {
                      // Show a cancel page or return to cart
                      console.log("Error: " + data);
                  }
                  
              }, opts.productDivId).then(function() {

                  console.log("Producto dibujado: " + opts.productDivId);

                  itemsProcessed++;

                  if(itemsProcessed === buttonsPromises.length) {
                      console.log("Todos los productos dibujados");

                      $(".input-num-articles").TouchSpin({
                          min: 1, 
                          max: 100}
                      );
                  }

              });

          });
      
      }

      Parse.$ = jQuery;
      Parse.initialize("dVVty0n8MrhMhTusZHskFKJADY2HmG17KWW2TpQ9", "ZauyN5aDZHeWgWp5W73U4qL6yCMm66Hf67QZNy5q");
      Parse.serverURL = 'https://parseapi.back4app.com';

      var Product = Parse.Object.extend("Product");
      var query = new Parse.Query(Product);
      query.find({
        success: function(results) {

          var promises = [];

          for (var i = 0; i < results.length; i++) {

            var object = results[i];

            var divId = "paypal-button-" + i;
            var inputId = "num-articles" + i;

            var item = '<div class="col-sm-6 col-md-4">' +
                          '<div class="thumbnail">' +
                            '<img src="' + object.get("image").url() + '" alt="">' +
                            '<div class="caption">' +
                              '<h3>' + object.get("title") + '</h3>' +
                              '<p>$' + object.get("price") + '</p>' + 
                              '<div id="' + divId + '"></div>' +
                              '<input class="input-num-articles" id="' + inputId + '" type="text" value="1" name="' + inputId + '">'+
                            '</div>' +
                          '</div>' +
                        '</div>';

            $("#items-list").append(item);

            promises.push({
                productName: object.get("title"),
                productDescription: object.get("title") + " talla M",
                productPrice: object.get("price"),
                productInputId: '#' + inputId,
                productDivId: '#' + divId
            });

          }

          renderPaypalButtons(promises);

        },
        error: function(error) {
          alert("Error: " + error.code + " " + error.message);
        }
      });


    </script>

  </body>
</html>
