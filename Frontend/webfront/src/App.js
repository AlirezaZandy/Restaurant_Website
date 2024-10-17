import "./App.css";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Layout from "./pages/Layout";
import Home from "./pages/Home";
import NoPage from "./pages/NoPage";
import LogIn from "./pages/LogIn";
import SingUp from "./pages/SingUp";
import Profile from "./pages/Profile";
import Cook from "./pages/Cook";

function App() {
  return (
    <div className="App">
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Layout />}>
            <Route path="home" element={<Home />} />
            <Route path="sinqup" element={<SingUp />} />
            <Route path="login" element={<LogIn />} />
            <Route path="profile" element={<Profile />} />
            <Route path="cook" element={<Cook />} />
            <Route path="*" element={<NoPage />} />
          </Route>
        </Routes>
      </BrowserRouter>
    </div>
  );
}

export default App;
