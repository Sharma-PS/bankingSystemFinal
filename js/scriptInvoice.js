window.addEventListener("load", event => {
   const cta = document.querySelector(".cta"),
      container = document.querySelector(".container"),
      receipt = document.querySelector(".receipt");

   cta.addEventListener("click", printReceipt);

   function printReceipt() {
      container.classList.add("anime");
      cta.innerHTML = "";

      container.style.height = receipt.clientHeight + 45 + "px";
   }
});