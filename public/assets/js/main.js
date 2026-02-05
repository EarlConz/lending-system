document.addEventListener("DOMContentLoaded", () => {
  const sourceRadios = document.querySelectorAll(
    'input[name="source_of_fund"]',
  );
  const panels = document.querySelectorAll("[data-source-panel]");

  const togglePanels = () => {
  const checkedRadio = document.querySelector(
    'input[name="source_of_fund"]:checked'
  );

  const selectedValue = checkedRadio ? checkedRadio.value : "none";

  panels.forEach(panel => {
    const shouldShow =
      selectedValue !== "none" &&
      panel.dataset.source === selectedValue;

    panel.classList.toggle("is-active", shouldShow);
  });
};

  sourceRadios.forEach((radio) => {
    radio.addEventListener("change", togglePanels);
  });

  togglePanels();

  const beneficiariesTable = document.querySelector(".beneficiaries table");
  if (beneficiariesTable) {
    const tbody = beneficiariesTable.querySelector("tbody");
    const entryRow = beneficiariesTable.querySelector("[data-beneficiary-entry]");
    const addButton = entryRow
      ? entryRow.querySelector('[data-action="add-beneficiary"]')
      : null;
    const emptyRow = beneficiariesTable.querySelector("[data-empty-row]");

    if (tbody && entryRow && addButton) {
      let beneficiaryIndex = tbody.querySelectorAll(
        "[data-beneficiary-row]",
      ).length;

      const clearErrors = () => {
        entryRow
          .querySelectorAll("[data-beneficiary-field].input-error")
          .forEach((field) => field.classList.remove("input-error"));
      };

      const updateRowNumbers = () => {
        const rows = tbody.querySelectorAll("[data-beneficiary-row]");
        rows.forEach((row, index) => {
          const indexCell = row.querySelector("td");
          if (indexCell) {
            indexCell.textContent = String(index + 1);
          }
        });

        if (emptyRow) {
          emptyRow.hidden = rows.length > 0;
        }
      };

      const createHiddenInput = (name, value) => {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = name;
        input.value = value;
        return input;
      };

      addButton.addEventListener("click", () => {
        clearErrors();

        const values = {};
        const fields = entryRow.querySelectorAll("[data-beneficiary-field]");

        fields.forEach((field) => {
          values[field.dataset.beneficiaryField] = field.value.trim();
        });

        const requiredFields = ["relation", "first_name", "last_name"];
        let hasError = false;

        requiredFields.forEach((key) => {
          const field = entryRow.querySelector(`[data-beneficiary-field="${key}"]`);
          if (field && !values[key]) {
            field.classList.add("input-error");
            hasError = true;
          }
        });

        if (hasError) {
          return;
        }

        const row = document.createElement("tr");
        row.setAttribute("data-beneficiary-row", "");

        const cells = [
          "",
          values.relation,
          values.first_name,
          values.middle_name || "-",
          values.last_name,
          values.birthdate || "-",
          values.gender || "-",
        ];

        cells.forEach((text, index) => {
          const cell = document.createElement("td");

          if (index === cells.length - 1) {
            const wrapper = document.createElement("div");
            wrapper.className = "beneficiary-cell";

            const label = document.createElement("span");
            label.textContent = text;
            wrapper.appendChild(label);

            const removeButton = document.createElement("button");
            removeButton.type = "button";
            removeButton.className = "btn ghost small danger";
            removeButton.textContent = "Remove";
            removeButton.addEventListener("click", () => {
              row.remove();
              updateRowNumbers();
            });

            wrapper.appendChild(removeButton);
            cell.appendChild(wrapper);
          } else {
            cell.textContent = text;
          }

          row.appendChild(cell);
        });

        Object.entries(values).forEach(([key, value]) => {
          row.appendChild(
            createHiddenInput(`beneficiaries[${beneficiaryIndex}][${key}]`, value),
          );
        });

        beneficiaryIndex += 1;
        tbody.appendChild(row);

        fields.forEach((field) => {
          field.value = "";
        });

        updateRowNumbers();
      });

      entryRow.querySelectorAll("[data-beneficiary-field]").forEach((field) => {
        field.addEventListener("input", () => {
          field.classList.remove("input-error");
        });
      });

      updateRowNumbers();
    }
  }

  const usedToggles = document.querySelectorAll("[data-used-toggle]");
  if (usedToggles.length) {
    const toggleUsedPanels = () => {
      usedToggles.forEach((select) => {
        const key = select.dataset.usedToggle;
        if (!key) {
          return;
        }
        const panel = document.querySelector(`[data-used-panel="${key}"]`);
        if (!panel) {
          return;
        }
        panel.classList.toggle("is-active", select.value === "Used");
      });
    };

    usedToggles.forEach((select) => {
      select.addEventListener("change", toggleUsedPanels);
    });

    toggleUsedPanels();
  }

  const modalRoot = document.querySelector("[data-modal-root]");
  if (modalRoot) {
    const overlay = modalRoot.querySelector("[data-modal-overlay]");
    const closeButtons = modalRoot.querySelectorAll("[data-modal-close]");

    const openModal = () => {
      modalRoot.classList.remove("tw-hidden");
      modalRoot.style.display = "block";
      document.body.style.overflow = "hidden";
    };

    const closeModal = () => {
      modalRoot.classList.add("tw-hidden");
      modalRoot.style.display = "none";
      document.body.style.overflow = "";
      const url = new URL(window.location.href);
      url.searchParams.delete("mode");
      url.searchParams.delete("id");
      window.history.replaceState({}, "", url.toString());
    };

    if (modalRoot.dataset.openModal === "1") {
      openModal();
    }

    closeButtons.forEach((button) => {
      button.addEventListener("click", closeModal);
    });

    if (overlay) {
      overlay.addEventListener("click", closeModal);
    }

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        closeModal();
      }
    });

    const tabGroup = modalRoot.querySelector("[data-tab-group]");
    if (tabGroup) {
      const tabButtons = tabGroup.querySelectorAll("[data-tab-target]");
      const panels = modalRoot.querySelectorAll("[data-tab-panel]");

      const setActiveTab = (target) => {
        tabButtons.forEach((button) => {
          const isActive = button.dataset.tabTarget === target;
          button.classList.toggle("tw-bg-sky-200", isActive);
          button.classList.toggle("tw-bg-sky-100", !isActive);
        });

        panels.forEach((panel) => {
          panel.style.display =
            panel.dataset.tabPanel === target ? "" : "none";
        });
      };

      tabButtons.forEach((button) => {
        button.addEventListener("click", () => {
          setActiveTab(button.dataset.tabTarget);
        });
      });

      const initialTab = tabButtons[0]?.dataset.tabTarget;
      if (initialTab) {
        setActiveTab(initialTab);
      }
    }
  }

  const statusFilter = document.querySelector("[data-status-filter]");
  if (statusFilter) {
    const checkboxes = statusFilter.querySelectorAll("[data-status-option]");
    const updateStatus = () => {
      const activeChecked = statusFilter.querySelector(
        '[data-status-option="Active"]',
      )?.checked;
      const inactiveChecked = statusFilter.querySelector(
        '[data-status-option="Inactive"]',
      )?.checked;
      let statusValue = "Active";

      if (activeChecked && inactiveChecked) {
        statusValue = "All";
      } else if (inactiveChecked) {
        statusValue = "Inactive";
      } else if (activeChecked) {
        statusValue = "Active";
      }

      const url = new URL(window.location.href);
      url.searchParams.set("status", statusValue);
      url.searchParams.delete("mode");
      url.searchParams.delete("id");
      window.location.href = url.toString();
    };

    checkboxes.forEach((checkbox) => {
      checkbox.addEventListener("change", updateStatus);
    });
  }
});
