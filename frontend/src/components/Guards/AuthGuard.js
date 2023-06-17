import {useNavigate, Navigate} from "react-router-dom";
import {useEffect, useState} from "react";
import axios from "axios";

const AuthGuard = ({children}) => {

    const navigate = useNavigate();

    const [user, setUser] = useState(null);

    useEffect(() => {
        const token = localStorage.getItem('token');

        if (token) {

            const backendUrl = process.env.REACT_APP_BACKEND_URL;

            const config = {
                headers: { Authorization: `Bearer ${token}` }
            };

            axios.get(backendUrl + '/api/check-auth', config).then(
                    response => {
                        setUser(response.data.user)
                    }
                ).catch(
                    err => {
                        localStorage.removeItem('token');
                        navigate('/');
                        console.log(err)
                    }
                );

        } else {
            navigate('/login');
        }

    }, [])

    if (!user) {
        return null;
    }

    if (!localStorage.getItem('token')) {
        return <Navigate to={"/login"}/>
    }

    return children;

}

export default AuthGuard;