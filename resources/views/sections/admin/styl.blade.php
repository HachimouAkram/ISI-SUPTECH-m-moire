@include('admin.script')

<script>
    const root = document.documentElement;
    const toggleBtn = document.getElementById("toggleTheme");

    // Charger la couleur sauvegardée
    if (localStorage.getItem("themePrimary")) {
        root.style.setProperty("--primary", localStorage.getItem("themePrimary"));
    }

    toggleBtn.addEventListener("click", () => {
        let current = getComputedStyle(root).getPropertyValue("--primary").trim();
        let newColor = (current === "#EB1616" || current === "rgb(235, 22, 22)")
            ? "#3498db"
            : "#EB1616";

        root.style.setProperty("--primary", newColor);
        localStorage.setItem("themePrimary", newColor);
    });
</script>
