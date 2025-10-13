function ChangesPassowrdAPI() {
  const BtnChangesPassword = document.getElementById("BtnChangesPassword");
  const form = document.getElementById("formChangesPassword");

  if (!BtnChangesPassword || !form) {
    return;
  }
  BtnChangesPassword.addEventListener("click", async () => {
    const formData = new FormData(form);
    const plainFormData = Object.fromEntries(formData.entries());
    const jsonFormData = JSON.stringify(plainFormData);

    const response = await fetch("/Perfumeria/API/account/ChangePassword.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: jsonFormData,
    });

    const data = await response.json();
    if (data.success) {
      alert(data.message);
      location.reload();
    } else {
      alert(data.errors.join("\n"));
    }
  });
}

export default ChangesPassowrdAPI;
