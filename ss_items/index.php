<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geenius Store</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

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
        <!-- <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div> -->
      </div>
    </nav>

    <div class="container">

      <div class="row">

        <div class="col-sm-6 col-md-4">
          <div class="thumbnail">
            <img src="http://image.made-in-china.com/43f34j00sZWtfASoClpT/Used-Clothing-Used-Clothe-and-Second-Hand-Clothes-for-African-Market-FCD-002-.jpg" alt="Playera">
            <div class="caption">
              <h3>Playera Nike</h3>
              <p>$198</p>
              <div id="paypal-button-1"></div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-md-4">
          <div class="thumbnail">
            <img src="http://www.michaldrivingschool.co.uk/images/michaldrivingschool.co.uk/Hurley-Core-Fleece-Boys-Hoodie-Dark-Grey-Heather-Clothes-Y76b2609n-65ZG.jpg" alt="Sudadera">
            <div class="caption">
              <h3>Sudadera Hurley</h3>
              <p>$490</p>
              <div id="paypal-button-2"></div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-md-4">
          <div class="thumbnail">
            <img src="http://theclothestore.com/image/cache/catalog/Clothes/11front-300x300.jpg" alt="Blusa">
            <div class="caption">
              <h3>Blusa</h3>
              <p>$230</p>
              <div id="paypal-button-3"></div>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /.container -->

    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript">

      var CREATE_PAYMENT_URL  = '../server_services/create-payment.php';
      var EXECUTE_PAYMENT_URL = '../server_services/execute-payment.php';

           
      paypal.Button.render({

        env: 'sandbox', // Or 'live'

        locale: 'es_ES',

        style: {
          label: 'checkout',
          size:  'small',    // small | medium | large | responsive
          shape: 'rect',     // pill | rect
          color: 'black'      // gold | blue | silver | black
        },


        client: {
          sandbox: 'AdLP7TfHOHls5OU6jM-hxJtfJCJLF599FsAhkpCrkhKw5FOKNa1PrCJ8cbiyNurH97bM4T7Tf5OL5c_v'
        },

        commit: true, 

        payment: function() {

          var transactionsArray = {
            "amount":
            { 
              "total":"198",
              "currency":"MXN"
            }, 
            "item_list":
            {
              "items": [
                {
                      "name":"Playera Nike",
                      "description":"Playera Nike talla M color negro",
                      "quantity":"1",
                      "price":"198",
                      "currency":"MXN"
                }]
            },
            "description":"Compra desde Geenius Store"
          };

          // Set up the data you need to pass to your server
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

        onAuthorize: function(data) {

          console.dir(data);

          // Set up the data you need to pass to your server
          var dataArray = {
              paymentID: data.paymentID,
              payerID: data.payerID
          };
          
          return paypal.request.post(EXECUTE_PAYMENT_URL, dataArray).then(function() {
              alert("Payment complete!")
              // The payment is complete!
              // You can now show a confirmation message to the customer
          },function(error) {
              console.log("Error: " + error);
          });

        }

      }, '#paypal-button-1');


      // BUTTON 2

      paypal.Button.render({

        env: 'sandbox', // Or 'sandbox'

        locale: 'es_ES',

        style: {
          label: 'checkout',
          size:  'small',    // small | medium | large | responsive
          shape: 'rect',     // pill | rect
          color: 'black'      // gold | blue | silver | black
        },


        client: {
          sandbox: 'AdLP7TfHOHls5OU6jM-hxJtfJCJLF599FsAhkpCrkhKw5FOKNa1PrCJ8cbiyNurH97bM4T7Tf5OL5c_v'
        },

        commit: true, 

        payment: function() {
          
          var transactionsArray = {
            "amount":
            { 
              "total":"490",
              "currency":"MXN"
            }, 
            "item_list":
            {
              "items": [
                {
                      "name":"Sudadera Hurley",
                      "description":"Sudadera Hurley bordada",
                      "quantity":"1",
                      "price":"490",
                      "currency":"MXN"
                }]
            },
            "description":"Compra desde Geenius Store"
          };

          // Set up the data you need to pass to your server
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

        onAuthorize: function(data) {

          console.dir(data);

          // Set up the data you need to pass to your server
          var dataArray = {
              paymentID: data.paymentID,
              payerID: data.payerID
          };
          
          return paypal.request.post(EXECUTE_PAYMENT_URL, dataArray).then(function() {
              alert("Payment complete!")
              // The payment is complete!
              // You can now show a confirmation message to the customer
          },function(error) {
              console.log("Error: " + error);
          });

        }

      }, '#paypal-button-2');


      // BUTTON 3

      paypal.Button.render({

        env: 'sandbox', // Or 'sandbox'

        locale: 'es_ES',

        style: {
          label: 'checkout',
          size:  'small',    // small | medium | large | responsive
          shape: 'rect',     // pill | rect
          color: 'black'      // gold | blue | silver | black
        },


        client: {
          sandbox: 'AdLP7TfHOHls5OU6jM-hxJtfJCJLF599FsAhkpCrkhKw5FOKNa1PrCJ8cbiyNurH97bM4T7Tf5OL5c_v'
        },

        commit: true, 

        payment: function() {

          var transactionsArray = {
            "amount":
            { 
              "total":"230",
              "currency":"MXN"
            }, 
            "item_list":
            {
              "items": [
                {
                      "name":"Blusa",
                      "description":"Blusa cafe talla M",
                      "quantity":"1",
                      "price":"230",
                      "currency":"MXN"
                }]
            },
            "description":"Compra desde Geenius Store"
          };

          // Set up the data you need to pass to your server
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

        onAuthorize: function(data) {

          console.dir(data);

          // Set up the data you need to pass to your server
          var dataArray = {
              paymentID: data.paymentID,
              payerID: data.payerID
          };
          
          return paypal.request.post(EXECUTE_PAYMENT_URL, dataArray).then(function() {
              alert("Payment complete!")
              // The payment is complete!
              // You can now show a confirmation message to the customer
          },function(error) {
              console.log("Error: " + error);
          });

        }

      }, '#paypal-button-3');


    </script>

  </body>
</html>
