import $ from 'jquery';
import {commonModal} from "../component/modal";
const {getRoute, httpRequest, trans} = require('./common');

$(document).ready(function (event) {
  const publicKey = $('#form-payment').data('public-key');
if(publicKey ==null) {
  return
}
  var stripe = new Stripe(publicKey); // recuparation de la clé public

  var elements = stripe.elements();
  var style = {
    base: {
      iconColor: '#666EE8',
      color: '#31325F',
      lineHeight: '40px',
      fontWeight: 300,
      fontFamily: 'Helvetica Neue',
      fontSize: '15px',

      '::placeholder': {
        color: '#CFD7E0',
      },
    },
  };

  var cardNumberElement = elements.create('cardNumber', {
    style: style
  });
  cardNumberElement.mount('#card-number-element');

  var cardExpiryElement = elements.create('cardExpiry', {
    style: style
  });
  cardExpiryElement.mount('#card-expiry-element');

  var cardCvcElement = elements.create('cardCvc', {
    style: style
  });
  cardCvcElement.mount('#card-cvc-element');

  function setOutcome(result) {
    var successElement = document.querySelector('.success');
    var errorElement = document.querySelector('.error');
    successElement.classList.remove('visible');
    errorElement.classList.remove('visible');

    if (result.token) {
      var $doc = $(document);
      $.ajax({
        url: getRoute('api_web_edit_payment', {card_id: $doc.find('#form-payment').data('card-id')}),
        type: 'POST',
        cache: false,
        data: $doc.find('form').serializeArray(),
        success: (data) => {
          stripe.handleCardPayment(
            data.data.clientSecret, cardNumberElement).then(function (result) {
            if (result.error) {
              // Display error.message in your UI.
              var errorElement = document.querySelector('.error');
              errorElement.classList.remove('visible');
              //removeMesageError();
              errorElement.textContent = result.error.message;
              errorElement.classList.add('visible');
            } else {
              var $form = $(document).find('form');
              console.log($form);
              //$form.querySelector('input[name="payment_intent_id"]').setAttribute('value', result.paymentIntent.id);
              $form.submit();
            }
          });
        }
      });

    } else if (result.error) {
      errorElement.textContent = result.error.message;
      errorElement.classList.add('visible');
    }
  }

  var cardBrandToPfClass = {
    'visa': 'pf-visa',
    'mastercard': 'pf-mastercard',
    'amex': 'pf-american-express',
    'discover': 'pf-discover',
    'diners': 'pf-diners',
    'jcb': 'pf-jcb',
    'unknown': 'pf-credit-card',
  };

  function setBrandIcon(brand) {
    var brandIconElement = document.getElementById('brand-icon');
    var pfClass = 'pf-credit-card';
    if (brand in cardBrandToPfClass) {
      pfClass = cardBrandToPfClass[brand];
    }
    for (var i = brandIconElement.classList.length - 1; i >= 0; i--) {
      brandIconElement.classList.remove(brandIconElement.classList[i]);
    }
    brandIconElement.classList.add('pf');
    brandIconElement.classList.add(pfClass);
  }

  cardNumberElement.on('change', function (event) {
    // Switch brand logo
    if (event.brand) {
      setBrandIcon(event.brand);
    }

    setOutcome(event);
  });

  document.querySelector('form').addEventListener('submit', function (e) {
    e.preventDefault();
    var options = {
      address_zip: document.getElementById('postal-code').value,
    };
    stripe.createToken(cardNumberElement, options).then(setOutcome);
  });
});
