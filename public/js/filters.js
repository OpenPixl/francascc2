window.onload = () => {
    const FiltersForms = document.getElementById('filters');
    document.querySelectorAll('#filters input').forEach(input => {
        input.addEventListener('change', () => {

            // j'intercepte les clics et ses données.
            const Form = new FormData(FiltersForms);
            const result = [];
            // construction de la "QueryString"
            const query = new URLSearchParams();

            Form.forEach((value,key) => {
                result.push(value);
                console.log(key ,result.toString())
            })

            // on récupère l'url active
            const url = new URL(window.location.href);
            console.log(url);
        });
    });
}
