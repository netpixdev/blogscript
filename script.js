document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email-input');
    const policyContainer = document.getElementById('privacy-policy-container');

    emailInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            policyContainer.classList.remove('hidden');
        } else {
            policyContainer.classList.add('hidden');
        }
    });
});
