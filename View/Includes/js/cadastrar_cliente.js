const modal = document.getElementById("modalCliente");

function chamaModal(){
    modal.classList.remove("oculta");
    modal.classList.add("chama");
}

let nome = document.getElementById("nome");
nome.addEventListener("change",async function(e){
    e.preventDefault();

    try {
        const response = await fetch("/infotech/api/action_cadastro_cliente.php");
        const result = await response.json();

       if (result.status == 200) {
        document.getElementById("modalClienteMensagem").textContent = result.msg;
        chamaModal();
       } else {
        alert("nao chama")
       }
    
    } catch (error) {
        console.log(error)
    }
})