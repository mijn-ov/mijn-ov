window.addEventListener('load', init)
window.addEventListener('resize', updateTransform);


let chatInput;
let helpText;
let chatForum;
let chatText;
let appSplash;
let messageArea;
let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let messages = [];
let userBubbles = [];
let botBubbles = [];

function init() {
    chatInput = document.getElementById('chat-box');
    helpText = document.getElementById('help-text');
    chatForum = document.getElementById('chatbox');
    appSplash = document.getElementById('app-splash');
    messageArea = document.getElementById('message-area');

    chatForum.addEventListener('submit', function (e) {
        e.preventDefault();

        if (chatInput.value !== '') {
            submitChat();
        }
    })
}

function createUserBubble(text) {
    let chatBubble = document.createElement('div');
    chatBubble.classList.add('chat-bubble-user');
    chatBubble.innerText = text;
    messageArea.appendChild(chatBubble);

    let elementWidth = chatBubble.offsetWidth;
    chatBubble.style.transform = 'translateX(calc(50vw - 25px - ' + elementWidth / 2 + 'px))';

    userBubbles.push(chatBubble)
}

function createBotBubble(text) {
    let chatBubble = document.createElement('div');
    chatBubble.classList.add('chat-bubble-bot');
    chatBubble.innerText = text;
    messageArea.appendChild(chatBubble);

    let elementWidth = chatBubble.offsetWidth;
    chatBubble.style.transform = 'translateX(calc(-50vw + 25px + ' + elementWidth / 2 + 'px))';

    botBubbles.push(chatBubble)
}

function updateTransform() {
    for (let bubble of userBubbles) {
        let elementWidth = bubble.offsetWidth;
        bubble.style.transform = 'translateX(calc(50vw - 25px - ' + elementWidth / 2 + 'px))';
    }
    for (let bubble of botBubbles) {
        let elementWidth = bubble.offsetWidth;
        bubble.style.transform = 'translateX(calc(-50vw + 25px + ' + elementWidth / 2 + 'px))';
    }
}

function submitChat() {
    helpText.remove()
    appSplash.remove()

    chatText = chatInput.value
    chatInput.value = '';

    createUserBubble(chatText);

    let newMessage = {
        agent: 'user',
        message: chatText
    };

    messages.push(newMessage);
    console.log(messages);

    messageArea.scrollTop = messageArea.scrollHeight;

    fetch(`/submit-message`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            message: chatText,
            history: messages,
        })
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            receiveMessage(data)
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });

}

async function receiveMessage(data) {

    let jsonResponse = JSON.parse(data.response)

    try {
        const response = await fetch(`${jsonResponse.url}`, {
            headers: {
                'Accept': 'application/json',
                'Ocp-Apim-Subscription-Key': 'eae2b92d3a49458f80503a5bb6f7df14'
            }
        });


        if (response.ok) {
            let array = [];
            const data = await response.json();
            for (let stop of data.trips[0].legs[0].stops) {
                array.push([stop.lng, stop.lat])
            }
            console.log(data.trips[0]);


        } else {
            console.error('Fout bij ophalen reisadvies:', response.statusText);
            console.log({error: "An error occured while generating MijnOV's response"});
        }
    } catch (error) {
        console.error('Er is een fout opgetreden:', error);
    }

    console.log(jsonResponse)

    createBotBubble(jsonResponse.beschrijving);

    let newMessage = {
        agent: 'bot',
        message: jsonResponse.beschrijving,
        data: jsonResponse.data,
    };

    messages.push(newMessage);
    console.log(messages);

    messageArea.scrollTop = messageArea.scrollHeight;
}
