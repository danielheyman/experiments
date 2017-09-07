import React, { Component } from 'react';
import { ToastAndroid, Alert, AsyncStorage } from 'react-native';
import { Text, ListItem, List, Container, Header, Title, Content, Footer, FooterTab, Button, Left, Right, Body, Icon } from 'native-base';

export default class Rewards extends Component {
    state = {
        tasks: []
    }
    
    componentWillMount() {
        this.props.addToSync((tasks) => this.setState({ tasks: tasks }))
    }
    
    use(task) {
        task.used += task.countToReward
        this.props.save(this.state.tasks)
    }
    
    plural(count, unit) {
        if(!unit) return count + ' more times'
        return count + ' more ' + unit + (count == 1 ? '' : 's') 
    }
    
    render() {
        var items = [];
        this.state.tasks.forEach(function(task, i) {
            if(task.reward == '') return;
            let available = parseInt((task.count - task.used) / task.countToReward)
            items.push(
                <ListItem key={i}>
                    <Left>
                        <Body>
                            <Text>{ task.reward }</Text>
                            <Text note>{ available } available to use!</Text>
                            <Text note>{ task.title } { this.plural(task.countToReward - (task.count % task.countToReward), task.unit) } for reward.</Text>
                        </Body>
                    </Left>
                    <Right>
                        <Button onPress={() => available ? Alert.alert(
                              'Are you sure you want to use ' + task.reward + '?',
                              'This is not reversable.',
                              [
                                {text: 'Use Me', onPress: () => this.use(task)},
                                {text: 'Cancel', style: 'cancel'},
                              ]
                          ) : ToastAndroid.show('No uses available', ToastAndroid.SHORT)}>
                            <Text>Use</Text>
                        </Button>
                    </Right>
                </ListItem>
            );
        }.bind(this));
        
        return (
            <Container>
                <Content>
                    <List>
                        { items }
                    </List>
                </Content>
            </Container>
        );
    }
}
