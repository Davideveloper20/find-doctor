
$(function(){
  var handler = ePayco.checkout.configure({
    key: '255f4b666f2bc98605834dc0c32b6b67',
    test: false
  })
  var data = {
    //Parametros compra (obligatorio)
    name: plan_name,
    description: plan_desc,
    invoice: pay_t,
    currency: "cop",
    amount: pay_c,
    tax_base: pay_c,
    tax: "0",
    country: "co",
    lang: "es",

    //Onpage="false" - Standard="true"
    external: "false",


    //Atributos opcionales
    extra1: pay_p,
    extra2: pay_i,
    extra3: pay_u,
    confirmation: pay_link,
    response: "https://conexioncibernetica.com/demo6/finddoctor/payments/pay",
  }
  handler.open(data)
})