function DarkMode() {
  const BtnDarkMode = document.querySelector(".dark-mode");
  const Body = document.querySelector("body");

  // Verifica si el usuario prefiere modo oscuro
  const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

  if (prefersDark) {
    Body.classList.add("Dark__Mode--Active");
  }

  BtnDarkMode.addEventListener("click", () => {
    Body.classList.toggle("Dark__Mode--Active");
  });
}

export default DarkMode;
