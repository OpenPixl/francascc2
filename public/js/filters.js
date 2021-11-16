window.onload = () => {
    const FiltersForms = document.getElementById('filters');
    document.querySelectorAll('#filters input').forEach(input => {
        input.addEventListener('change', () => {
            // j'intercepte les clics et ses données.
            const Form = new FormData(FiltersForms);
            // construction de la "QueryString"
            const Params = new URLSearchParams();
            //alimentation de la "QueryString"
            Form.forEach((value,key) => {
                Params.append(key, value);
            })

            const url = '/webapp/ressources/filter';
            axios
                .get(url + "?" + Params.toString())
                .then(response => {
                    // rafraichissement du tableau
                    const liste = document.getElementById('liste').innerHTML = response.data.liste;
                })
        });
    });
}
