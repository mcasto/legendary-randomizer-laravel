import React, { useState } from "react";
import { useEffect } from "react";
import { Head } from "@inertiajs/react";

function App() {
  const [numPlayers, setNumPlayers] = useState(null);
  const [heroes, setHeroes] = useState([]);
  const [enemies, setEnemies] = useState([]);
  const [selectedView, setSelectedView] = useState("heroes"); // Switch between heroes or enemies

  // Fetch data from the backend when the numPlayers changes
  useEffect(() => {
    if (numPlayers) {
      // Replace with your API call
      fetch(`/api/game-setup/${numPlayers}`)
        .then((res) => res.json())
        .then((data) => {
          setHeroes(data.heroes);
          setEnemies(data.enemies);
        });
    }
  }, [numPlayers]);

  return (
    <>
      <Head title="Game Setup" />

      <div className="flex flex-col h-screen bg-gray-100">
        {/* Toolbar with player selection buttons */}
        <div className="bg-blue-500 p-4 text-white flex justify-center">
          {Array.from({ length: 5 }, (_, index) => index + 1).map((players) => (
            <button
              key={players}
              className="mx-2 px-4 py-2 bg-blue-700 hover:bg-blue-600 rounded"
              onClick={() => setNumPlayers(players)}
            >
              {players} Players
            </button>
          ))}
        </div>

        {/* View toggles for heroes and enemies (showing in tabs) */}
        <div className="flex-grow flex">
          {/* Mobile-first: Stack view for smaller screens */}
          <div className="w-full lg:w-1/2 p-4">
            <div className="tabs">
              <button
                className={`tab ${selectedView === "heroes" ? "active" : ""}`}
                onClick={() => setSelectedView("heroes")}
              >
                Heroes
              </button>
              <button
                className={`tab ${selectedView === "enemies" ? "active" : ""}`}
                onClick={() => setSelectedView("enemies")}
              >
                Enemies
              </button>
            </div>

            {/* Conditional rendering based on selected view */}
            {selectedView === "heroes" ? (
              <HeroesComponent heroes={heroes} />
            ) : (
              <EnemiesComponent enemies={enemies} />
            )}
          </div>

          {/* On medium screens and above, show both components side-by-side */}
          <div className="hidden lg:block lg:w-1/2 p-4">
            <div className="flex">
              <div className="w-1/2">
                <HeroesComponent heroes={heroes} />
              </div>
              <div className="w-1/2">
                <EnemiesComponent enemies={enemies} />
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}

const HeroesComponent = ({ heroes }) => (
  <div>
    <h2>Heroes</h2>
    <ul>
      {heroes.map((hero) => (
        <li key={hero.id}>{hero.name}</li>
      ))}
    </ul>
  </div>
);

const EnemiesComponent = ({ enemies }) => (
  <div>
    <h2>Enemies</h2>
    <ul>
      {enemies.map((enemy) => (
        <li key={enemy.id}>{enemy.name}</li>
      ))}
    </ul>
  </div>
);

export default App;
