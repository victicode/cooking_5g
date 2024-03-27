export const func = {
    numberFormat: (number) => {
        number = `${number}`;

        let formattedNumber = "";
        let newNumber = "";

        // Si el numero empieza por el valor "-" (numero negativo)
        // If the number starts with "-" is negative
        if(number[0] == "-") {
            newNumber = number.replace(/\,/g, '').substring(1); // replace all ',' with '' and not delete the negative symbol
        } else{
            newNumber = number.replace(/\,/g, ''); // replace all ',' with ''
        }

        // If the number has decimals
        if(number.indexOf(".") >= 0) {
            newNumber = newNumber.substring(0, newNumber.indexOf("."));
        }

        // Put '.' for each 3 characters
        for (var j, i = newNumber.length - 1, j = 0; i >= 0; i--, j++) {
            formattedNumber = newNumber.charAt(i) + ((j > 0) && (j % 3 == 0)? ",": "") + formattedNumber;
        }

        // If the number has decimals, push to the formattedNumber
        if(number.indexOf(".") >= 0){
            formattedNumber += number.substring(number.indexOf("."));
        }

        if(number[0] == "-") {
            return "-" + formattedNumber; // Return the value with negative symbol
        }

        return formattedNumber;
    },
    isValidEmail: (email) => {
        return new RegExp(/\S+@\S+\.\S+/).test(String(email).toLowerCase());
    },
    isValidNumberFormat: (text, event) => {
        var charCode = (event.which) ? event.which : event.keyCode;
        if (charCode == 46) {
            //Check if the text already contains the . character
            if (text.indexOf('.') === -1) {
                return true;
            } else {
                event.preventDefault()
            }
        } else {
            if (charCode > 31 && (charCode < 48 || charCode > 57)){
                event.preventDefault()
            }
        }
        return true;
    },
    orderFormat: (id) => {
       return '0000000'.slice( 0, 6 - id.toString().length ) + id 
    },
}
window.$number_format = func.numberFormat
window.$orderFormat = func.orderFormat