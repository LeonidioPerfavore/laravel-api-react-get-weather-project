import {useEffect} from "react";
import axios from "axios";
import {useLocation} from "react-router-dom";
import { useNavigate } from 'react-router-dom';
import Preloader from "./Preloader";

export const GoogleCallback = () => {

    const location = useLocation();
    const navigate = useNavigate();

    useEffect(() => {

        const backendUrl = process.env.REACT_APP_BACKEND_URL;

        let config = {
            method: 'get',
            url: backendUrl+`/api/google/login/${location.search}`
        };

        axios(config)
            .then(response => {
                    localStorage.setItem('token', response.data.token);
                    navigate('/');
                }
            ).catch(
            error => {
                if (error.response) {
                    if (error.response.status === 422) {
                        console.log('Invalid credentials')
                    }else{
                        console.log(error)
                    }
                } else {
                    console.log('Something went wrong')
                }
                localStorage.removeItem('token')
                navigate('/login');
            }
        );

    }, []);

    return (<Preloader />)
};

export default GoogleCallback;
