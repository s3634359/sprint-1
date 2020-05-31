import React from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';

import MaterialTable from 'material-table';
import { Typography, Container } from "@material-ui/core";
import { makeStyles } from '@material-ui/core/styles';

const useStyles = makeStyles((theme) => ({
    heroContent: {
        backgroundColor: theme.palette.background.paper,
        padding: theme.spacing(8, 0, 6),
    },
}));

function Home(props) {
    const [users, setUsers] = React.useState(JSON.parse(props.data));
    const classes = useStyles();

    const list = [];
    for (const user of users) {
        list.push({ name: user.name, email: user.email, position: user.position });
    }

    return (
        <React.Fragment>
            <main>
                {/* Hero unit */}
                <div className={classes.heroContent}>
                    <Container maxWidth="sm">
                        <Typography component="h1" variant="h2" align="center" color="textPrimary" gutterBottom>
                            Account
                    </Typography>
                        <Typography variant="h5" align="center" color="textSecondary" paragraph>
                            Welcome
                        </Typography>
                    </Container>
                </div>
            </main>
        </React.Fragment>
    );
}

export default Home;

if (document.getElementById('home')) {
    var data = document.getElementById('home').getAttribute('data');
    ReactDOM.render(<Home data={data} />, document.getElementById('home'));
}
