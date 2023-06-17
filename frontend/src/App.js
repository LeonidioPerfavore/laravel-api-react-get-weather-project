import './App.css';
import {BrowserRouter as Router, Route, Routes} from "react-router-dom";
import Login from "./Pages/LoginPage/Login";
import Home from "./Pages/HomePage/Home";
import AuthGuard from "./components/Guards/AuthGuard";
import GuestGuard from "./components/Guards/GuestGuard";
import NotFound from "./Pages/NotFound";
import Registration from "./Pages/RegistrationPage/Registration";
import GoogleCallback from "./components/GoogleCallback";

function App() {
    return (
        <Router>
            <Routes>
                <Route path="/login" element={
                    <GuestGuard>
                        <Login/>
                    </GuestGuard>
                }/>
                <Route path="/registration" element={
                    <GuestGuard>
                        <Registration/>
                    </GuestGuard>
                }/>
                <Route path="/google/callback" element={
                    <GuestGuard>
                        <GoogleCallback/>
                    </GuestGuard>
                }/>
                <Route path="/" element={
                    <AuthGuard>
                        <Home/>
                    </AuthGuard>
                }/>
                <Route path="*" element={<NotFound/>}/>
            </Routes>
        </Router>
    );
}

export default App;
