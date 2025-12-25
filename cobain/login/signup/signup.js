const avatarInput = document.getElementById("avatar");
const fileNameEl = document.getElementById("fileName");
const preview = document.getElementById("preview");

if (avatarInput) {
	avatarInput.addEventListener("change", () => {
		const file = avatarInput.files?.[0];
		if (!file) {
			fileNameEl.textContent = "Belum ada file";
			preview.innerHTML = "";
			return;
		}

		fileNameEl.textContent = file.name;

		if (!file.type.startsWith("image/")) {
			preview.innerHTML = "";
			return;
		}

		const reader = new FileReader();
		reader.onload = e => {
			preview.innerHTML = `<img src="${e.target?.result || ""}" alt="Preview">`;
		};
		reader.readAsDataURL(file);
	});
}
