window.onload = () => {
    const FiltersForm = document.querySelector("#filters");
    //boucle sur les input
    document.querySelectorAll("#filters input").forEach(input => {
        input.addEventListener("change", () =>{

            //recupération des donées
            const Form = new FormData(FiltersForm);

            //fabrication du "queryString"
            const Params = new URLSearchParams();

            Form.forEach((value, key) => {
                Params.append(key, value);
            });
            
            //récupération de l'url active
            const Url = new URL(window.location.href);
            

            //on lance la requet ajax
            fetch(Url.pathname + "?" + Params.toString() + "&ajax=1", {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            }).then(response =>{
                console.log(response)
            }).catch(e => alert(e));
            
            
        });
    });
}