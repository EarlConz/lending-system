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

  const initTabs = (scope) => {
    if (!scope) {
      return;
    }

    const tabGroup = scope.querySelector("[data-tab-group]");
    if (!tabGroup) {
      return;
    }

    const tabButtons = tabGroup.querySelectorAll("[data-tab-target]");
    const panels = scope.querySelectorAll("[data-tab-panel]");

    if (!tabButtons.length || !panels.length) {
      return;
    }

    const setActiveTab = (target) => {
      tabButtons.forEach((button) => {
        const isActive = button.dataset.tabTarget === target;
        button.classList.toggle("tw-bg-accent-3", isActive);
        button.classList.toggle("tw-bg-surface-2", !isActive);
        button.classList.toggle("tw-text-ink", isActive);
        button.classList.toggle("tw-text-muted", !isActive);
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
  };

  document
    .querySelectorAll("[data-tab-scope]")
    .forEach((scope) => initTabs(scope));

  const initLoanApplicationGate = () => {
    const gate = document.querySelector("[data-loan-application-gate]");
    const picker = document.querySelector("[data-client-picker-input]");
    if (!gate || !picker) {
      return;
    }

    const listId = picker.getAttribute("list");
    const list = listId ? document.getElementById(listId) : null;
    const form = document.getElementById("loan-application-form");
    if (!list || !form) {
      return;
    }

    const modalRoot = document.querySelector("[data-loan-application-modal]");
    const openButton = document.querySelector("[data-loan-application-open]");
    const borrowerField = form.querySelector("[data-borrower-id-field]");
    const nameField = form.querySelector("[data-client-name-field]");
    const phoneField = form.querySelector("[data-client-phone-field]");
    const productSelect = form.querySelector("[data-loan-product-select]");
    const termField = form.querySelector("[data-loan-term-field]");
    const termUnitField = form.querySelector("[data-loan-term-unit-field]");
    const interestField = form.querySelector("[data-loan-interest-field]");
    const interestTypeField = form.querySelector('select[name="interest_type"]');
    const deductionInterestField = form.querySelector(
      "[data-loan-interest-deduction-field]",
    );
    const serviceChargeField = form.querySelector("[data-loan-service-charge-field]");
    const deductionClimbsField = form.querySelector(
      'input[name="deduction_climbs"]',
    );
    const totalDeductionsField = form.querySelector(
      'input[name="total_deductions"]',
    );
    const netProceedsField = form.querySelector('input[name="net_proceeds"]');
    const notarialField = form.querySelector("[data-loan-notarial-field]");
    const loanAmountField = form.querySelector("[data-loan-amount-field]");
    const actionButtons = document.querySelectorAll(
      "[data-loan-application-action]",
    );

    let toastRoot = document.querySelector(".toast-root");
    if (!toastRoot) {
      toastRoot = document.createElement("div");
      toastRoot.className = "toast-root";
      document.body.appendChild(toastRoot);
    }

    const showToast = (message) => {
      if (!message) {
        return;
      }
      const toast = document.createElement("div");
      toast.className = "toast";
      toast.textContent = message;
      toastRoot.appendChild(toast);
      requestAnimationFrame(() => {
        toast.classList.add("is-visible");
      });
      setTimeout(() => {
        toast.classList.remove("is-visible");
        toast.addEventListener("transitionend", () => toast.remove(), {
          once: true,
        });
      }, 2800);
    };

    const openLoanModal = () => {
      if (!modalRoot) {
        return;
      }
      modalRoot.classList.remove("tw-hidden");
      modalRoot.style.display = "block";
      document.body.style.overflow = "hidden";
    };

    const closeLoanModal = () => {
      if (!modalRoot) {
        return;
      }
      modalRoot.classList.add("tw-hidden");
      modalRoot.style.display = "none";
      document.body.style.overflow = "";
      const url = new URL(window.location.href);
      url.searchParams.delete("mode");
      url.searchParams.delete("id");
      window.history.replaceState({}, "", url.toString());
    };

    const isLoanModalOpen = () => {
      if (!modalRoot) {
        return false;
      }
      return !modalRoot.classList.contains("tw-hidden");
    };

    const lockGate = () => {
      gate.classList.add("loan-application-locked");
      gate.classList.remove("loan-application-ready");
      gate.dataset.clientSelected = "0";
      actionButtons.forEach((button) => {
        button.disabled = true;
      });
    };

    const unlockGate = () => {
      gate.classList.remove("loan-application-locked");
      gate.dataset.clientSelected = "1";
      actionButtons.forEach((button) => {
        button.disabled = false;
      });
    };

    const triggerPopIn = () => {
      gate.classList.remove("loan-application-ready");
      // Force reflow to restart animation.
      void gate.offsetWidth;
      gate.classList.add("loan-application-ready");
    };

    let loanProductMap = {};
    const productDataScript = document.getElementById("loanProductData");
    if (productDataScript) {
      try {
        loanProductMap = JSON.parse(productDataScript.textContent || "{}");
      } catch (error) {
        loanProductMap = {};
      }
    }

    const parseNumber = (value) => {
      if (value === null || value === undefined) {
        return null;
      }
      const cleaned = String(value).replace(/[, ]/g, "").trim();
      if (!cleaned) {
        return null;
      }
      const number = Number.parseFloat(cleaned);
      return Number.isFinite(number) ? number : null;
    };

    const formatMoney = (value) => {
      if (value === null || value === undefined || Number.isNaN(value)) {
        return "";
      }
      return value.toFixed(2);
    };

    const computeMaturityDate = () => {
      const releaseValue = form.querySelector('input[name="release_date"]')?.value;
      const termValue = termField?.value;
      const unitValue = termUnitField?.value;
      const maturityField = form.querySelector('input[name="maturity_date"]');

      if (!maturityField) {
        return;
      }

      if (!releaseValue || !termValue || !unitValue) {
        return;
      }

      const termNumber = parseNumber(termValue);
      if (termNumber === null) {
        return;
      }

      const releaseDate = new Date(releaseValue + "T00:00:00");
      if (Number.isNaN(releaseDate.getTime())) {
        return;
      }

      const maturityDate = new Date(releaseDate.getTime());
      switch (unitValue) {
        case "Days":
          maturityDate.setDate(maturityDate.getDate() + termNumber);
          break;
        case "Weeks":
          maturityDate.setDate(maturityDate.getDate() + termNumber * 7);
          break;
        case "Semi-Months":
          maturityDate.setDate(maturityDate.getDate() + termNumber * 15);
          break;
        case "Months":
          maturityDate.setMonth(maturityDate.getMonth() + termNumber);
          break;
        default:
          return;
      }

      if (Number.isNaN(maturityDate.getTime())) {
        return;
      }

      const year = maturityDate.getFullYear();
      const month = String(maturityDate.getMonth() + 1).padStart(2, "0");
      const day = String(maturityDate.getDate()).padStart(2, "0");
      maturityField.value = `${year}-${month}-${day}`;
    };

    const applyNotarialFee = (product) => {
      if (!notarialField) {
        return;
      }

      if (!product || product.notarial_used !== "Used") {
        notarialField.value = "";
        return;
      }

      const rateValue = parseNumber(product.notarial_rate_value);
      if (rateValue === null) {
        notarialField.value = "";
        return;
      }

      if (product.notarial_rate_option === "Amount (PHP)") {
        notarialField.value = formatMoney(rateValue);
        return;
      }

      const loanAmount = parseNumber(loanAmountField?.value);
      if (loanAmount === null) {
        notarialField.value = "";
        return;
      }

      const computed = (loanAmount * rateValue) / 100;
      notarialField.value = formatMoney(computed);
    };

    const termToMonths = (termValue, unit) => {
      if (!termValue || !unit) {
        return null;
      }

      const termNumber = parseNumber(termValue);
      if (termNumber === null) {
        return null;
      }

      switch (unit) {
        case "Months":
          return termNumber;
        case "Days":
          return termNumber / 30;
        case "Weeks":
          return termNumber / 4.345;
        case "Semi-Months":
          return termNumber / 2;
        default:
          return null;
      }
    };

    const termToDays = (termValue, unit) => {
      if (!termValue || !unit) {
        return null;
      }

      const termNumber = parseNumber(termValue);
      if (termNumber === null) {
        return null;
      }

      switch (unit) {
        case "Days":
          return termNumber;
        case "Weeks":
          return termNumber * 7;
        case "Semi-Months":
          return termNumber * 15;
        case "Months":
          return termNumber * 30;
        default:
          return null;
      }
    };

    const applyDeductionInterest = (product) => {
      if (!deductionInterestField) {
        return;
      }

      if (!product) {
        deductionInterestField.value = "";
        return;
      }

      const interestType = interestTypeField?.value || "";
      if (interestType === "Diminishing") {
        deductionInterestField.value = formatMoney(0);
        return;
      }

      const loanAmount = parseNumber(loanAmountField?.value);
      const interestRate = parseNumber(interestField?.value);
      const termDays = termToDays(termField?.value, termUnitField?.value);

      if (loanAmount === null || interestRate === null || termDays === null) {
        deductionInterestField.value = "";
        return;
      }

      const computed = (loanAmount * termDays * (interestRate / 100)) / 360;
      deductionInterestField.value = formatMoney(computed);
    };

    const applyServiceChargeDeduction = (product) => {
      if (!serviceChargeField) {
        return;
      }

      if (!product) {
        serviceChargeField.value = "";
        return;
      }

      const serviceChargeAmount = parseNumber(product.service_charge);
      if (serviceChargeAmount === null) {
        serviceChargeField.value = "";
        return;
      }

      serviceChargeField.value = formatMoney(serviceChargeAmount);
    };

    const computeTotals = () => {
      if (!totalDeductionsField || !netProceedsField) {
        return;
      }

      const deductionValues = [
        parseNumber(deductionInterestField?.value),
        parseNumber(serviceChargeField?.value),
        parseNumber(deductionClimbsField?.value),
        parseNumber(notarialField?.value),
      ];

      const hasAnyValue = deductionValues.some(
        (value) => value !== null && value !== undefined,
      );

      const loanAmount = parseNumber(loanAmountField?.value);
      if (!hasAnyValue) {
        if (loanAmount === null) {
          totalDeductionsField.value = "";
          netProceedsField.value = "";
          return;
        }
        totalDeductionsField.value = formatMoney(0);
        netProceedsField.value = formatMoney(loanAmount);
        return;
      }

      const total = deductionValues.reduce(
        (sum, value) => sum + (value ?? 0),
        0,
      );

      totalDeductionsField.value = formatMoney(total);

      if (loanAmount === null) {
        netProceedsField.value = "";
        return;
      }

      netProceedsField.value = formatMoney(loanAmount - total);
    };

    const applyProductAutofill = () => {
      if (!productSelect) {
        return;
      }

      const product = loanProductMap[productSelect.value] || null;
      if (!product) {
        if (termField) {
          termField.value = "";
        }
        if (termUnitField) {
          termUnitField.value = "";
        }
        if (interestField) {
          interestField.value = "";
        }
        if (deductionInterestField) {
          deductionInterestField.value = "";
        }
        if (serviceChargeField) {
          serviceChargeField.value = "";
        }
        if (totalDeductionsField) {
          totalDeductionsField.value = "";
        }
        if (netProceedsField) {
          netProceedsField.value = "";
        }
        applyNotarialFee(null);
        return;
      }

      if (termUnitField && product.term_unit && product.term_unit !== "Not Used") {
        termUnitField.value = product.term_unit;
      }
      if (termField && product.default_term) {
        termField.value = product.default_term;
      }
      if (interestField && product.interest_rate) {
        interestField.value = product.interest_rate;
      }
      applyNotarialFee(product);
      applyDeductionInterest(product);
      applyServiceChargeDeduction(product);
      computeTotals();
    };

    const resetLoanForm = () => {
      const fields = form.querySelectorAll("input, select, textarea");
      fields.forEach((field) => {
        const name = field.getAttribute("name") || "";
        if (name === "csrf_token" || name === "action") {
          return;
        }

        if (field.type === "checkbox" || field.type === "radio") {
          field.checked = false;
          return;
        }

        if (field.tagName === "SELECT") {
          field.selectedIndex = 0;
          return;
        }

        if (field.type === "file") {
          field.value = "";
          return;
        }

        field.value = "";
      });
    };

    let currentBorrowerId =
      gate.dataset.selectedBorrowerId ||
      borrowerField?.value?.trim() ||
      "";
    let lastCommittedBorrowerId = currentBorrowerId;

    if (gate.dataset.clientSelected === "1" && currentBorrowerId) {
      unlockGate();
    } else {
      lockGate();
      currentBorrowerId = "";
      gate.dataset.selectedBorrowerId = "";
    }

    const clearSelection = () => {
      if (currentBorrowerId) {
        resetLoanForm();
      }
      currentBorrowerId = "";
      gate.dataset.selectedBorrowerId = "";
      if (borrowerField) {
        borrowerField.value = "";
      }
      if (nameField) {
        nameField.value = "";
      }
      if (phoneField) {
        phoneField.value = "";
      }
      lockGate();
      closeLoanModal();
    };

    const applySelection = (option) => {
      const borrowerId = option.dataset.borrowerId || "";
      const name = option.dataset.name || "";
      const phone = option.dataset.phone || "";

      if (!borrowerId) {
        clearSelection();
        return;
      }

      if (currentBorrowerId === borrowerId) {
        if (!isLoanModalOpen()) {
          openLoanModal();
        }
        return;
      }

      const isChange =
        lastCommittedBorrowerId && borrowerId !== lastCommittedBorrowerId;

      if (isChange) {
        resetLoanForm();
        showToast("Client changed, form reset.");
      } else {
        showToast(name ? `Client selected: ${name}` : "Client selected.");
      }

      currentBorrowerId = borrowerId;
      lastCommittedBorrowerId = borrowerId;
      gate.dataset.selectedBorrowerId = borrowerId;

      if (borrowerField) {
        borrowerField.value = borrowerId;
      }
      if (nameField) {
        nameField.value = name;
      }
      if (phoneField) {
        phoneField.value = phone;
      }

      unlockGate();
      triggerPopIn();
      openLoanModal();
    };

    const handlePickerChange = () => {
      const value = picker.value.trim();
      if (!value) {
        clearSelection();
        return;
      }

      const match = Array.from(list.options).find(
        (option) => option.value === value,
      );

      if (!match) {
        clearSelection();
        return;
      }

      applySelection(match);
    };

    picker.addEventListener("input", handlePickerChange);
    picker.addEventListener("change", handlePickerChange);
    if (openButton) {
      openButton.addEventListener("click", () => {
        if (currentBorrowerId) {
          openLoanModal();
        }
      });
    }

    if (productSelect) {
      productSelect.addEventListener("change", applyProductAutofill);
      applyProductAutofill();
    }

    if (loanAmountField) {
      loanAmountField.addEventListener("input", () => {
        const product = productSelect
          ? loanProductMap[productSelect.value] || null
          : null;
        applyNotarialFee(product);
        applyDeductionInterest(product);
        applyServiceChargeDeduction(product);
        computeTotals();
      });
    }

    if (termField) {
      termField.addEventListener("input", () => {
        const product = productSelect
          ? loanProductMap[productSelect.value] || null
          : null;
        applyDeductionInterest(product);
        applyServiceChargeDeduction(product);
        computeTotals();
        computeMaturityDate();
      });
    }

    if (termUnitField) {
      termUnitField.addEventListener("change", () => {
        const product = productSelect
          ? loanProductMap[productSelect.value] || null
          : null;
        applyDeductionInterest(product);
        applyServiceChargeDeduction(product);
        computeTotals();
        computeMaturityDate();
      });
    }

    if (interestField) {
      interestField.addEventListener("input", () => {
        const product = productSelect
          ? loanProductMap[productSelect.value] || null
          : null;
        applyDeductionInterest(product);
        computeTotals();
      });
    }

    if (interestTypeField) {
      interestTypeField.addEventListener("change", () => {
        if (interestTypeField.value === "Diminishing" && interestField) {
          interestField.value = "0.00";
          const product = productSelect
            ? loanProductMap[productSelect.value] || null
            : null;
          applyDeductionInterest(product);
          computeTotals();
        }
      });
    }

    const releaseDateField = form.querySelector('input[name="release_date"]');
    if (releaseDateField && !releaseDateField.value) {
      const today = new Date();
      const year = today.getFullYear();
      const month = String(today.getMonth() + 1).padStart(2, "0");
      const day = String(today.getDate()).padStart(2, "0");
      releaseDateField.value = `${year}-${month}-${day}`;
    }

    if (releaseDateField) {
      releaseDateField.addEventListener("change", computeMaturityDate);
    }

    computeMaturityDate();

    if (deductionClimbsField) {
      deductionClimbsField.addEventListener("input", computeTotals);
    }

    if (notarialField) {
      notarialField.addEventListener("input", computeTotals);
    }
  };

  initLoanApplicationGate();

  const initCacobemModal = () => {
    const modalRoot = document.querySelector("[data-cacobem-modal]");
    if (!modalRoot) {
      return;
    }

    const overlay = modalRoot.querySelector("[data-cacobem-overlay]");
    const closeButtons = modalRoot.querySelectorAll("[data-cacobem-close]");
    const openButtons = document.querySelectorAll("[data-cacobem-open]");

    const openModal = () => {
      modalRoot.classList.remove("tw-hidden");
      modalRoot.style.display = "block";
      document.body.style.overflow = "hidden";
    };

    const closeModal = () => {
      modalRoot.classList.add("tw-hidden");
      modalRoot.style.display = "none";
      document.body.style.overflow = "";
    };

    openButtons.forEach((button) => {
      button.addEventListener("click", openModal);
    });

    closeButtons.forEach((button) => {
      button.addEventListener("click", closeModal);
    });

    if (overlay) {
      overlay.addEventListener("click", closeModal);
    }

    if (modalRoot.dataset.openModal === "1") {
      openModal();
    }

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        closeModal();
      }
    });
  };

  initCacobemModal();

  document.querySelectorAll("[data-print]").forEach((button) => {
    button.addEventListener("click", () => window.print());
  });

  const initCacobemUnderlineWidths = () => {
    const preview = document.querySelector(".cacobem-doc-full");
    if (!preview) {
      return;
    }

    const previewStyle = window.getComputedStyle(preview);
    const measure = document.createElement("span");
    measure.style.position = "absolute";
    measure.style.visibility = "hidden";
    measure.style.whiteSpace = "pre";
    measure.style.fontFamily = previewStyle.fontFamily;
    measure.style.fontSize = previewStyle.fontSize;
    measure.style.fontWeight = previewStyle.fontWeight;
    const sampleCount = 100;
    measure.textContent = "_".repeat(sampleCount);
    document.body.appendChild(measure);
    const underscoreWidth = measure.getBoundingClientRect().width / sampleCount;
    measure.remove();

    preview.querySelectorAll("input[data-underline]").forEach((input) => {
      const count = parseInt(input.dataset.underline || "0", 10);
      if (!count) {
        return;
      }
      const width = Math.ceil(underscoreWidth * count);
      input.style.width = `${width}px`;
    });
  };

  const initCacobemPreviewZoom = () => {
    const preview = document.querySelector(".cacobem-doc-full");
    if (!preview) {
      return;
    }

    const updateZoom = () => {
      const container = preview.parentElement || preview;
      const containerWidth = container.clientWidth;
      if (!containerWidth) {
        return;
      }

      const baseWidth = 8.5 * 96;
      const scale = Math.min(1.35, Math.max(0.8, containerWidth / baseWidth));
      preview.style.setProperty("--cacobem-preview-zoom", scale.toFixed(3));
    };

    updateZoom();
    window.addEventListener("resize", updateZoom);
  };

  initCacobemUnderlineWidths();
  initCacobemPreviewZoom();
  window.addEventListener("resize", initCacobemUnderlineWidths);
  if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(() => {
      initCacobemUnderlineWidths();
    });
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

    initTabs(modalRoot);
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
