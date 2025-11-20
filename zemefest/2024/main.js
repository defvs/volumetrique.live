async function getLastN(campaignName, limit, type) {
    // Construct the URL with query parameters
    const url = new URL('https://zemefest.volumetrique.live/gofundme.php');
    url.searchParams.append('campaign_name', campaignName);
    url.searchParams.append('limit', limit);
    url.searchParams.append('type', type);

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

function displayComments(comments) {
    const listGroup = document.querySelector('#list-group');
    listGroup.innerHTML = '';

    comments.forEach(commentData => {
        const name = commentData.donation.is_anonymous ? 'Anonymous' : commentData.donation.name;
        const donationAmount = commentData.donation.amount;
        const comment = commentData.comment.comment;

        const listItem = document.createElement('li');
        listItem.classList.add('card');

        listItem.innerHTML = `
        <div class="card-body">
      <span class="badge text-bg-secondary comment-amount">$${donationAmount}</span> <b class="comment-name">${name}</b><br>
      <span class="comment-comment">${comment}</span></div>
    `;

        listGroup.appendChild(listItem);
    });
}


function displayDonations(donations) {
    const listGroup = document.querySelector('#list-group');
    listGroup.innerHTML = '';

    donations.forEach(commentData => {
        const name = commentData.name;
        const donationAmount = commentData.amount;

        const listItem = document.createElement('li');
        listItem.classList.add('card');

        listItem.innerHTML = `
        <div class="card-body">
      <span class="badge text-bg-secondary comment-amount">$${donationAmount}</span> <b class="comment-name">${name}</b></div>
    `;

        listGroup.appendChild(listItem);
    });
}
