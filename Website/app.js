// 2. Render the PayPal button (moved from inline script)
paypal.Buttons({
    // Sets up the transaction when the button is clicked
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                description: "E-Commerce Lab Payment",
                amount: {
                    value: '10.00'  // Amount to charge
                }
            }]
        });
    },
    // Handle the successful transaction
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            const resultDiv = document.getElementById('result');
            resultDiv.style.color = 'green';
            resultDiv.innerHTML = `✅ Payment Successfull! <br> Transaction ID: ${details.id}<br> Payer: ${details.payer.name.given_name}`;
            
            // Alert for screenshot purposes
            alert('Payment Confirmed! Check the console for details.');
            console.log('Transaction Details:', details);
        });
    },
    // Handle errors
    onError: function(err) {
        const resultDiv = document.getElementById('result');
        resultDiv.style.color = 'red';
        resultDiv.innerHTML = '❌ Payment Failed or Error Occurred. Please try again.';
        console.error(err);
    }
}).render('#paypal-button-container');
