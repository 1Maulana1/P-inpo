const passwordInput = document.getElementById("password");
const toggleButton = document.getElementById("togglePassword");

if (passwordInput && toggleButton) {
	toggleButton.addEventListener("click", () => {
		const isHidden = passwordInput.type === "password";
		passwordInput.type = isHidden ? "text" : "password";
		toggleButton.textContent = isHidden ? "HIDE" : "SHOW";
	});
}
