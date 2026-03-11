document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("productFilter");
    const productContainer = document.querySelector("#productResult");

    const productName = document.getElementById("product_name");
    const productBrand = document.getElementById("product_brand");

    function loadProduct(){
            let formData = new FormData(form);
            let url = form.action;

            fetch(url, {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(html => {

                let parser = new DOMParser();
                let doc = parser.parseFromString(html, "text/html");

                let newProducts = doc.querySelector("#productResult").innerHTML;

                productContainer.innerHTML = newProducts;

            });
    }

    if(productName){
        productName.addEventListener('keyup',loadProduct);
    }

    if(productBrand){
        productBrand.addEventListener('change',loadProduct);
    }


});
