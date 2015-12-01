(function() {
    window.addEvent('domready', function() {
        return;
        checkGartengroesse(this);
        $$('.gartengroesse .radio').addEvent('click', function(event){
              checkGartengroesse(this);
          });
    });

    /**
     * Disable newsletter, if customer only buys a "schutznetz"
     * @param elRadio
     */
    var checkGartengroesse = function(elRadio){
        var arrRadio = [];
        $$('.gartengroesse .radio').each(function(el){
            arrRadio.push(el);
        });

        var radioValue = '';
        for(var i = 0; i < arrRadio.length; i++){
            if(arrRadio[i].checked){
                radioValue = arrRadio[i].value;
            }
        }

        if(radioValue == 'nur-netz'){
            console.log(radioValue);
            $$('input[name=newsletter').setProperties({
                'disabled': 'disabled',
                'checked': ''
            });
        }else{
            $$('input[name=newsletter').setProperties({
                'disabled': ''
            });
        }
    }
})();
