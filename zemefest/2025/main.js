async function getLastN(campaignName, limit, type) {
    // Construct the URL with query parameters
    const url = new URL('https://zemefest.volumetrique.live/2025/tiltify.php');

    try {
        // Fetch the data from the endpoint
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`Error: ${response.status} - ${response.statusText}`);
        }

        // Parse the JSON response
        const data = await response.json();

        // Return the data for further processing
        return data;
    } catch (error) {
        console.error('Error fetching data:', error);
        return null; // Return null in case of an error
    }
}

function displayDonations(donations) {
    const listGroup = document.querySelector('#list-group');
    listGroup.innerHTML = '';

    donations.forEach(donation => {
        const name = donation.donor_name || 'Anonymous';
        const donationAmount = donation.amount.value;
        const donationCurrency = donation.amount.currency;

        const listItem = document.createElement('li');
        listItem.classList.add('card');

        listItem.innerHTML = `
        <div class="card-body">
          <span class="badge text-bg-secondary comment-amount">${donationCurrency} ${donationAmount}</span> <b class="comment-name">${name}</b>
        </div>
      `;

        listGroup.appendChild(listItem);
    });
}
