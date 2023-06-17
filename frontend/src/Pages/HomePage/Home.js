import {useNavigate} from 'react-router-dom';
import {useEffect, useState} from "react";
import publicIp from "public-ip";
import Preloader from "../../components/Preloader";
import {getInstanceAxios} from "../../utils/helper";

export const Home = () => {

    const navigate = useNavigate();
    const [weather, setWeather] = useState(null);
    const [user, setUser] = useState(null);
    const [loader, setLoader] = useState(true);

    const getWeather = async () => {

        try {
            const ip = await publicIp.v4();
            const backendUrl = process.env.REACT_APP_BACKEND_URL;

           await getInstanceAxios({ clientIp: ip })
               .get(backendUrl + '/api/home').then(response => {
                setUser(response.data.user);
                setWeather(response.data.main);
            }).catch(error => {
                   localStorage.removeItem('token');
                   navigate('/login');
                console.log(error)
            });

        } catch (error) {
            localStorage.removeItem('token');
            navigate('/login');
        }

        setLoader(false);
    }

    const logout = () => { localStorage.removeItem('token'); navigate('/login') }

    function getOrdinalSuffix(day) {
        const suffixes = ['st', 'nd', 'rd'];
        const relevantDigits = day % 100;
        return suffixes[(relevantDigits - 1) % 10] || 'th';
    }

    const getDate = () => {
        const date = new Date();
        const day = date.getDate();
        const month = date.toLocaleString('default', { month: 'short' });
        const ordinalSuffix = getOrdinalSuffix(day);
        return `${day}${ordinalSuffix} ${month}`;
    }

    useEffect(() => {
        (async () => {
            await getWeather();
        })();
    }, []);

    return (
        <div className={'wrapper bg-main'}>
            {loader ? <Preloader /> :
                <div className="container">
                    <button onClick={logout} className={'logout-btn cursor-pointer mt-percent'}>LOGOUT</button>

                    {weather ?
                    <article className="widget">
                    <div className="weatherIcon"><i className="wi wi-day-cloudy"/></div>
                    <div className="weatherInfo">
                        {/** TEMP **/}
                        <div className="temperature"><span>{weather.temp}&deg;</span></div>
                        <div className="description">
                            <div className="place">
                                Min: {weather.temp_max} <br />
                                Max: {weather.temp_max} <br />
                                Humidity: {weather.humidity} <br />
                                Pressure: {weather.pressure} <br />
                            </div>
                        </div>
                    </div>
                    <div className="date">{getDate()}</div>
                </article>
                        :
                        <h4>Weather not found</h4>}
                </div>
            }
        </div>
    );
};

export default Home;
