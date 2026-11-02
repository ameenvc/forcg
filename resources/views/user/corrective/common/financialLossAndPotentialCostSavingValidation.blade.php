<script>
    const inputFinancialLoss = document.getElementById("financial_loss");
    const errorFinancialLoss = document.getElementById("error_financial_loss");

    const inputPotentialCostSaving = document.getElementById("potential_cost_savings");
    const errorPotentialCostSaving = document.getElementById("error_potential_cost_savings");

    inputFinancialLoss.setAttribute("maxlength", 32);
    inputPotentialCostSaving.setAttribute("maxlength", 32);

    inputFinancialLoss.addEventListener("input", () => {
        if (inputFinancialLoss.value.length >= 32) {
            inputFinancialLoss.value = inputFinancialLoss.value.substring(0, 32);
            errorFinancialLoss.textContent = "Maximum 32 characters Reached";
        } else {
            errorFinancialLoss.textContent = "";
        }
    });

    inputPotentialCostSaving.addEventListener("input", () => {
        if (inputPotentialCostSaving.value.length >= 32) {
            inputPotentialCostSaving.value = inputPotentialCostSaving.value.substring(0, 32);
            errorPotentialCostSaving.textContent = "Maximum 32 characters Reached";
        } else {
            errorPotentialCostSaving.textContent = "";
        }
    });
</script>
