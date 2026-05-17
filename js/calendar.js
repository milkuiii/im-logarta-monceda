document.addEventListener('DOMContentLoaded', () => {
    const dateInput = document.getElementById('reservation_date');
    if (!dateInput) return;

    if (!dateInput.value) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.value = today;
    }

    updateSlotAvailability();

    dateInput.addEventListener('change', updateSlotAvailability);
});

function updateSlotAvailability() {
    const dateInput = document.getElementById('reservation_date');
    if (!dateInput) return;

    const chosenDate = dateInput.value;
    if (!chosenDate) return;

    // Read the taken slots ledger arrays injected into the global window execution layout
    const ledger = window.takenSlotsLedger || [];

    document.querySelectorAll('.timeslot-label').forEach(label => {
        const slotValue = label.getAttribute('data-slot-value');
        const validationKey = chosenDate + "||" + slotValue;
        
        const radioInput = label.querySelector('input');
        const tileElement = label.querySelector('.timeslot-tile');

        if (ledger.includes(validationKey)) {
            // LOCK STATE: Timeslot matches an existing reservation entry
            radioInput.disabled = true;
            radioInput.checked = false;
            label.style.pointerEvents = 'none';
            
            tileElement.style.backgroundColor = "#ccc";
            tileElement.style.color = "#777";
            tileElement.style.borderColor = "transparent";
        } else {
            // OPEN STATE: Re-open interactive functionality if slot is free
            radioInput.disabled = false;
            label.style.pointerEvents = 'auto';
            
            tileElement.innerText = slotValue;
            tileElement.style.backgroundColor = "#fff";
            tileElement.style.color = "var(--text-dark)";
            tileElement.style.borderColor = "transparent";
        }
    });
}

function highlightSlot(radioInput) {
    if (radioInput.disabled) return;

    document.querySelectorAll('.timeslot-tile').forEach(tile => {
        const parentRadio = tile.parentElement.querySelector('input');
        if (!parentRadio.disabled) {
            tile.style.backgroundColor = '#fff';
            tile.style.color = 'var(--text-dark)';
            tile.style.borderColor = 'transparent';
        }
    });
    
    // Emphasize the currently clicked choice with your primary theme parameters
    const targetTile = radioInput.nextElementSibling;
    targetTile.style.backgroundColor = 'var(--accent-yellow)';
    targetTile.style.color = 'var(--primary-red)';
    targetTile.style.borderColor = 'var(--primary-red)';
}

// Attach the selection function to the global scope so inline onclick attributes can access it
window.highlightSlot = highlightSlot;