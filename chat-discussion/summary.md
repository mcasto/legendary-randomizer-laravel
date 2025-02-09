#### **Environment Details**  
- **Tech Stack**: Laravel 11 (backend) + Inertia.js + React (frontend)  
- **Database**: MariaDB  
- **Platform**: Mac  

### **Summary of initial discussion
**Discussion Summary: "Laravel Legendary"**

### **Project Context**
- **Frameworks/Tools**: You're working with **Laravel**, **React**, and **Inertia** to port your existing **Quasar/Vue3** app into this new setup.
- **Primary Goal**: The project is a **randomizer** for *Legendary: A Marvel Deck-Building Game*, with all the game logic being handled by the backend (Laravel), and the frontend (React) displaying the results.
- **State Management**: You were using **Pinia** in Vue, but with the new backend-driven logic, you don’t need complex state management for the frontend. Instead, the frontend will primarily handle data display, and backend routes will process the game setup and randomization.

### **Key Points About Routing and Data Flow**
1. **Inertia vs. React Router**:
   - **Inertia** is used to keep server-side routing but enhance it with a client-side feel, which is appropriate for your setup where the backend (Laravel) does most of the logic and passes the data to the frontend.
   - **React Router** is an alternative for client-side routing but isn't necessary if you stick with Inertia for server-side routing.

2. **Backend Logic**:
   - The backend will handle tasks like randomization and setting up the game, and will return the game setup data to the React frontend.
   - Laravel’s **controllers** will be used to fetch the data from the database and return it to React via **Inertia**.

3. **Frontend Data Handling**:
   - The frontend will receive data from the backend (e.g., game setup) and display it to the user.
   - For user settings, the frontend will send a request to update settings, and the backend will save them to the database.

4. **State Management**:
   - For this app, **Pinia** and other complex state management solutions are unnecessary. Instead, you'll manage local component state with React's **`useState`** or **React Context** if necessary (e.g., for authentication or preferences).
   - **React Query** or **Wretch** can be used for fetching data from the backend, especially for actions like randomization.

5. **Wretch for API Calls**:
   - You’re using **Wretch** for HTTP requests instead of **Axios** or **Fetch**. It's a lightweight fetch library, perfect for making API calls like pulling randomizer data or updating user settings.

### **Current State**
* I have set up the authorization using Blaze's default tools.
* In the database, all the data related to the cards in the game is located in a table called "store".
* Each record in store has the following fields: list, _id, rec
    * list specifies which type of entity it is (schemes, masterminds, etc.)
    * _id is the id from an external data source where I pull my data from
    * rec is JSON that contains the details for the entity
        * Within that JSON is the id from the external source, the name of entity (mastermind's name, scheme name, villain group, henchmen name, hero name), and other data including a "set" field that I actually call "expansions"
* In the user table, there's a settings JSON field
    * settings contains an isAdmin setting for that user, the list of expansions that user has, and other settings to control their display preferences, whether to use the played count, and whether to use epic masterminds.
* I have a row of buttons at the top of my welcome page that let the user select the number of players they want to generate a game for
* I have a GenerateRandomizerController that's called when the user specifies the number of players
* It's called via Wretch get at '/api/randomizer/{numPlayers}'
* That controller calls out to another called WeightedShuffle that:
    1. removes epic masterminds if the user's settings specify to do so
    2. shuffles the entities of the specified list
        * if !$user->settings->usePlayedCount, then it just shuffles them randomly
        * otherwise, it ranks reorders the list based on the played_count for each item (played_count is pulled using a join from store to played_counts when the records are pulled from store), then it uses the "weighted-lotto-php" library with the use_order_as_weight. By putting ordering so played_counts are in ascending order, weighted-lotto-php shuffles them & with weight on putting the items played least at the top of the resulting array.

* GenerateRandomizerController calls WeightedShuffle for each list (schemes, masterminds, villains, henchmen, heroes) & builds a "masterList"

### **Next Step**
My next planned step is:

☐ build a Game model
    * candidates
    * deck
    ☐ build logic to build deck from candidates
    ☐ figure out how to manage handlers (code snippets to modify setup & deck), I think I want to do them as Controllers, but I'm not sure if that makes sense. That's our first topic of discussion for this session.
    ☐ build logic for handlers (these modify the setup & deck based on the parameters for the item they're associated with: usually schemes, occasionally a mastermind or villain [so far, might change with future expansions])
    ☐ make sure each element of the setup is fulfilled after the handlers run (e.g.: proper number of schemes, masterminds, villains, henchmen, heroes)

---

### **Deck Setup & Handler System Overview**  

#### **Deck Composition & Defaults**  
- A 2-player game starts with:  
  - **1 Scheme**  
  - **1 Mastermind**  
  - **2 Villain groups**  
  - **1 Henchman group**  
  - **5 Heroes**  
- Some entities modify this setup (e.g., the *Avengers vs. X-Men* scheme changes the hero count to 6, split between teams).  

#### **Handler Naming Convention**  
- Handlers are named as:  
  - `camelCase(name) . "-" . camelCase(expansion)`  
  - Example: `"avengersVsXMen-xmen"`  

#### **Deck Building Process**  
1. Select entities based on default setup.  
2. Check if each selected entity has a **corresponding handler** (name + expansion).  
3. Store handlers in a list.  
4. Execute handlers **in order**:  
   - **Scheme handlers first**, then **Mastermind handlers**, followed by **Villains, Henchmen, Heroes**.  
   - Handlers modify the deck as needed (e.g., adding/removing groups).  
5. If a handler changes the deck, check for **new handlers** and repeat the process.  
6. Ensure the final deck meets the required setup.  
7. If missing entities, pull from **candidates** (entities in the Master List but not yet used).  
8. If no valid candidates exist, assign a **fallback entity** (logged for debugging).  

#### **Conflicting Handlers & Always Leads**  
- Masterminds now have **built-in handlers** that automatically pull their "Always Leads" villain group.  
- If a scheme places a group somewhere else (e.g., Doombots in heroes, but Dr. Doom needs them in villains), handlers should attempt to **move them appropriately**.  
- If moving isn’t possible, a conflict **should be logged**.  

#### **Logging & Summary Output**  
- Track which handlers were executed and why.  
- Log all deck changes and potential conflicts.  
- If a required entity is missing, log fallback assignments.  
- Provide a **final deck summary** with explanations.  

#### **Factory-Based Handler System**  
- Handlers will use a **base handler interface** within a factory system.  
- This allows easy customization for handling special cases.  

---

### **Scalability & Maintainability Considerations**  

#### **1. Handler Organization & Structure**  
- Store handlers in a **dedicated directory**, grouped by type (e.g., `app/Handlers/Schemes`, `app/Handlers/Masterminds`).  
- Use a **base handler class/interface** for consistency.  
- Keep handlers **self-contained** and extend as needed.

#### **2. Automatic Handler Registration**  
- Use **reflection or directory scanning** for **dynamic handler registration**.  
- Automatically map handlers based on naming convention.

#### **3. Factory-Based Approach**  
- Implement a **factory pattern** to instantiate handlers dynamically based on entity names.  
- This allows for **easy addition** of new handlers without modifying core logic.

#### **4. Handler Execution Pipeline**  
- Maintain a **queue** to process handlers iteratively.  
- Ensure **dependency resolution** for proper execution order.  
- Add handlers to the queue dynamically if needed.

#### **5. Logging & Debugging**  
- Use **structured logging** to track deck changes, conflicts, and handler execution.  
- Implement a system to view past deck generations and adjustments for **debugging**.

### **Use Laravel's Illuminate\Support\Str to convert to camelCase for handler naming

---

### **Summary of most recent session (2025-02-05 09:28 America/Guayaquil)

### **Legendary Randomizer System Overview:**

#### **Master List & Weighted Shuffling**
- **Master List** contains *all* possible entities (schemes, masterminds, villains, henchmen, heroes) to be used in the deck. It takes user preferences (such as weighting, Epic Masterminds, etc.) into account using the `weightedShuffle->shuffle()` function, which returns a shuffled list.
- **Goal:** The Master List is the source of truth and controls the randomness and user preferences.

#### **Deck Building Process**
1. **Shuffling Master List**: The Master List is shuffled based on user preferences, ensuring dynamic randomness.
2. **Pull Entities from the List**: Items are pulled from the top of the shuffled list, and the deck is built in the required format (e.g., 1 scheme, 1 mastermind, etc.).
3. **Deck Composition**: Entities are drawn from the Master List and placed into their respective deck categories.

#### **Handler System**
- **Handler Role**: Handlers modify the deck as it’s being built. They ensure specific entities are placed correctly and meet any special requirements.
  - If a handler requires a specific entity, it checks if that entity is available in the Master List.
  - **Swapping Logic**: If the entity is still available, the handler will swap it with a random entity in the deck and push the swapped entity back to the head of the Master List.
  - If the entity is already in the deck, the handler will ensure it's placed in the correct category (e.g., schemes, masterminds, villains, heroes, henchmen).
  
#### **Conflict Handling & Logging**
- If a handler cannot place an entity correctly, conflicts are logged.
  - **Logging**: Track which handlers were executed, why actions were taken, and any conflicts encountered.
  - If a handler can’t place an entity, fallback logic is triggered (e.g., using an alternative entity or adjusting the deck).
  
#### **Deck Consistency**
- Handlers ensure the deck has the correct number of entities (e.g., 1 scheme, 1 mastermind, 2 villain groups) and that entities are placed in the proper sections.
- **Final Deck Verification**: The deck is reviewed to ensure it adheres to the required setup.

#### **Summary of Workflow:**
1. **Shuffle the Master List** based on user settings.
2. **Build the deck** by pulling entities from the top of the shuffled Master List.
3. **Run handlers** to swap, place, or adjust entities in the deck.
4. **Log actions** taken by handlers, including conflicts and deck changes.
5. **Ensure deck consistency** by confirming each section has the correct entities and quantities.

#### **Scalability Considerations:**
- Use **dynamic handler registration** to allow easy addition of new handlers.
- Organize handlers by type (e.g., schemes, masterminds) for maintainability.
- Implement **fallback mechanisms** for missing entities.
- Provide structured **logging** to track changes and conflicts.

---

My immediate question: Right now, if I build the handlers using the method we've been discussing here, there would be 112 handlers that actually perform a function. But, what I'm thinking about, is that each entity would have a handler, even if it does nothing more than return null or something. That would mean, right now, 1024 handler files with more to come in future expansions.

Due to the naming convention, it wouldn't have to search the handler directory to find the proper file, but ~90% of the time, the handler would do nothing, but it would still call it for each entity in the deck. A deck can have anywhere from 7 - ~20 entities.

That seems like it wouldn't be very efficient, but how much delay would it really cause in the assembly of a deck?

And, how much would that delay be with, say, 5000 entities (which is more than we'll likely ever have)?
