document.addEventListener("DOMContentLoaded", () => {
  const sourceRadios = document.querySelectorAll('input[name="source_of_fund"]');
  const panels = document.querySelectorAll("[data-source-panel]");

  const togglePanels = () => {
    if (!panels.length) {
      return;
    }

    let selectedValue = "none";
    for (let i = 0; i < sourceRadios.length; i += 1) {
      if (sourceRadios[i].checked) {
        selectedValue = sourceRadios[i].value;
        break;
      }
    }

    panels.forEach((panel) => {
      const panelSource = panel.dataset.source || "";
      if (!selectedValue || selectedValue === "none") {
        panel.hidden = true;
      } else {
        panel.hidden = panelSource !== selectedValue;
      }
    });
  };

  sourceRadios.forEach((radio) => {
    radio.addEventListener("change", togglePanels);
  });

  togglePanels();

  const beneficiariesTable = document.querySelector(".beneficiaries table");
  if (!beneficiariesTable) {
    return;
  }

  const tbody = beneficiariesTable.querySelector("tbody");
  const entryRow = beneficiariesTable.querySelector("[data-beneficiary-entry]");
  const addButton = entryRow
    ? entryRow.querySelector('[data-action="add-beneficiary"]')
    : null;
  const emptyRow = beneficiariesTable.querySelector("[data-empty-row]");

  if (!tbody || !entryRow || !addButton) {
    return;
  }

  let beneficiaryIndex = tbody.querySelectorAll("[data-beneficiary-row]").length;

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
        createHiddenInput(`beneficiaries[${beneficiaryIndex}][${key}]`, value)
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
});
