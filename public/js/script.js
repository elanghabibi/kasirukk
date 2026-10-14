
class ShowPassword {
  constructor(el) {
    this.container = el;
    this.input = this.container.querySelector(".password-input");
    this.btnIcon = this.container.querySelector(".password-btn-icon");

    this.showIcon = "bx-eye";
    this.hideIcon = "bx-eye-slash";

    this.handleToggle = this.handleToggle.bind(this);

    this.init();
  }

  init() {
    if (!this.container || !this.input || !this.btnIcon) return;
    this.btnIcon.addEventListener("click", this.handleToggle);
  }

  handleToggle() {
    const isPassword = this.input.type === "password";

    this.input.type = isPassword ? "text" : "password";

    this.renderIcon(isPassword);
  }

  renderIcon(isPassword) {
    this.btnIcon.classList.remove(this.showIcon, this.hideIcon);
    this.btnIcon.classList.add(isPassword ? this.hideIcon : this.showIcon);
  }
}

document.querySelectorAll(".show-password").forEach((el) => {
  new ShowPassword(el);
});


// Toast

class Toast {
    constructor({
        message = "",
        type = "info",
        title = "",
        duration = 4000
    }) {
        this.type = type;
        this.title = title;
        this.message = message;
        this.duration = duration;

        this.element = null;
        this.timer = null;
    }

    show() {
        const container = document.getElementById("toast-container");

        if (!container) return;

        this.element = this.createElement();

        container.appendChild(this.element);

        requestAnimationFrame(() => {
            this.element.classList.remove("translate-x-full", "opacity-0");
            this.element.classList.add("translate-x-0", "opacity-100");
        });

        this.timer = setTimeout(() => {
            this.hide();
        }, this.duration);
    }

    hide() {
        if (!this.element) return;

        clearTimeout(this.timer);

        this.element.classList.remove(
            "translate-x-0",
            "opacity-100"
        );

        this.element.classList.add(
            "translate-x-full",
            "opacity-0"
        );

        setTimeout(() => {
            this.element?.remove();
        }, 500);
    }

    createElement() {
        const toast = document.createElement("div");

        toast.className = `
            w-full
            rounded-2xl
            border
            border-gray-200
            bg-white
            shadow-lg
            p-4
            flex
            items-start
            gap-3
            transition-all
            duration-300
            translate-x-full
            opacity-0
        `;

        toast.innerHTML = `
            <div class="shrink-0">
                ${this.getIcon()}
            </div>

            <div class="min-w-0 flex-1">
                <h3 class="font-semibold text-gray-900">
                    ${this.title}
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    ${this.message}
                </p>
            </div>

            <button
                type="button"
                class="toast-close shrink-0 text-gray-400 hover:text-gray-600"
            >
                <i class="bx bx-x text-xl"></i>
            </button>
        `;

        toast
            .querySelector(".toast-close")
            .addEventListener("click", () => {
                this.hide();
            });

        return toast;
    }

    getIcon() {
        const icons = {
            success: `
                <div class="w-10 h-10 rounded-xl bg-lime-50 text-lime-600 flex items-center justify-center">
                    <i class="bx bx-check text-2xl"></i>
                </div>
            `,

            error: `
                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                    <i class="bx bx-x text-2xl"></i>
                </div>
            `,

            warning: `
                <div class="w-10 h-10 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center">
                    <i class="bx bx-alert-triangle text-2xl"></i>
                </div>
            `,

            info: `
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="bx bx-info-circle text-2xl"></i>
                </div>
            `
        };

        return icons[this.type] ?? icons.info;
    }
}

// Toast Manager

class ToastManager {

    static show(options) {
        const toast = new Toast(options);

        toast.show();

        return toast;
    }

    static success(message, title = "Berhasil") {
        return this.show({
            type: "success",
            title: title,
            message: message
        });
    }

    static error(message, title = "Gagal") {
        return this.show({
            type: "error",
            title: title,
            message: message
        });
    }

    static warning(message, title = "Peringatan") {
        return this.show({
            type: "warning",
            title: title,
            message: message
        });
    }

    static info(message, title = "Informasi") {
        return this.show({
            type: "info",
            title: title,
            message: message
        });
    }
}

// End Toast

// Loading Btn Form

class LoadingBtn {
  constructor(form) {
    this.form = form;
    this.btn = this.form.querySelector(".form-btn");
    this.text = this.btn.querySelector(".form-btntext");

    this.init();
  }

  init() {
    if (!this.btn || !this.text) return;
    this.form.addEventListener("submit", () => this.startLoad());
  }

  startLoad() {
    this.btn.disabled = true;
    this.btn.classList.remove("cursor-pointer");
    this.btn.classList.add("opacity-60");
    this.text.innerText = "Loading...";
  }
}

document.querySelectorAll(".form").forEach((el) => {
  new LoadingBtn(el);
});

// End Loading Btn Form

// Modal

class Modal {
  constructor(el) {
    this.el = el;
    this.btn = this.el.querySelector(".modal-btn");
    this.btnClose = this.el.querySelector(".modal-close")
    this.panel = this.el.querySelector(".modal-panel");
    this.isOpen = false;

    this.handleClickOutside = this.handleClickOutside.bind(this);

    this.init();
  }

  init() {
    if (!this.el || !this.btn || !this.panel) return;
    // this.btns.forEach((btn) => {
    //   btn.addEventListener("click", () => this.toggle());
    // });
    this.btn.addEventListener("click", () => this.toggle());
    this.btnClose.addEventListener("click", () => this.hidden())
  }

  toggle() {
    this.isOpen ? this.hidden() : this.show();
  }

  handleClickOutside(e) {
    if (!this.el.contains(e.target)) {
      this.hidden();
    }
  }

  show() {
    this.isOpen = true;
    this.panel.classList.remove(
      "transition-all",
      "duration-300",
      "opacity-0",
      "pointer-events-none"
    );
    this.panel.classList.add(
      "transition-all",
      "duration-300",
      "opacity-100",
      "pointer-events-auto"
    );

    document.addEventListener("click", this.handleClickOutside);
  }

  hidden() {
    this.isOpen = false;
    this.panel.classList.remove(
      "transition-all",
      "duration-300",
      "opacity-100",
      "pointer-events-auto"
    );
    this.panel.classList.add(
      "transition-all",
      "duration-300",
      "opacity-0",
      "pointer-events-none"
    );

    document.removeEventListener("click", this.handleClickOutside);
  }
}

document.querySelectorAll(".modal").forEach((el) => {
  new Modal(el);
});

// End Modal