import React, { useState, useEffect } from 'react';

function Home() {
  const [userInput, setUserInput] = useState('');
  const [chatHistory, setChatHistory] = useState([]);

  useEffect(() => {
    scrollToBottom();
  }, [chatHistory]);

  const scrollToBottom = () => {
    const chatContainer = document.getElementById('chat-container');
    chatContainer.scrollTop = chatContainer.scrollHeight;
  };

  const handleChange = (e) => {
    setUserInput(e.target.value);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch('http://localhost:8000/recommendations', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ userInput }),
      });
      const data = await response.json();
      setChatHistory([...chatHistory, { user: userInput, bot: data.routeRecommendations }]);
      setUserInput('');
    } catch (error) {
      console.error('Error:', error);
    }
  };

  return (
    <div className="flex justify-center items-center h-screen bg-gray-100">
      <div className="w-96 border rounded overflow-hidden shadow-lg bg-white">
        <div className="bg-gray-200 p-4">
          <h1 className="text-lg font-semibold text-center text-gray-800">Chat with the Public Transport Bot</h1>
        </div>
        <div className="chat-container h-80 overflow-y-scroll p-4" id="chat-container">
          {chatHistory.map((message, index) => (
            <div key={index} className="message">
              {message.user && (
                <div className="user-message">
                  <p className="bg-blue-200 border border-blue-300 text-blue-800 rounded-md p-3 mb-2">{message.user}</p>
                </div>
              )}
              {message.bot && (
                <div className="bot-message">
                  {message.bot.recommendations.map((recommendation, idx) => (
                    <div key={idx} className="recommendation bg-gray-100 border-b border-gray-300 p-3 mb-2 rounded">
                      <p className="title font-semibold text-blue-700">{recommendation.title}</p>
                      <p className="description text-gray-700">{recommendation.description}</p>
                      <p className="info italic text-gray-600">Info: {recommendation.info}</p>
                    </div>
                  ))}
                </div>
              )}
            </div>
          ))}
        </div>
        <form onSubmit={handleSubmit} className="p-4 flex">
          <input
            type="text"
            value={userInput}
            onChange={handleChange}
            placeholder="Enter your message..."
            className="flex-1 px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
          />
          <button type="submit" className="px-4 py-2 bg-blue-500 text-white rounded ml-2 hover:bg-blue-600">
            Send
          </button>
        </form>
      </div>
    </div>
  );
}

export default Home;
