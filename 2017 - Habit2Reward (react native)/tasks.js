import React, { Component } from 'react';
import { AsyncStorage } from 'react-native';
import { Badge, ActionSheet, Button, FooterTab, Footer, Body, Icon, CheckBox, List, ListItem, Left, Right, Container, Content, Text } from 'native-base';
import { Actions } from 'react-native-router-flux';
var DialogAndroid = require('react-native-dialogs');

const today = new Date().toJSON().slice(0,10).replace(/-/g,'/')
const month = new Date().toJSON().slice(0,7).replace(/-/g,'/')

export default class Tasks extends Component {
    state = {
        tasks: []
    }
    
    componentWillMount() {
        this.props.addToSync((tasks) => this.setState({ tasks: tasks }))
    }
    
    addNewTask(task) {
        this.state.tasks.push(task);
        this.props.save(this.state.tasks)
    }
    
    toggle(task) {
        task.checked = !task.checked
        if(task.checked) task.lastChecked = today
        if(!task.history.hasOwnProperty(month)) task.history[month] = 0
        if(task.type == 'unlimited') {
            var options = {
                title: task.title,
                content: 'How many ' + task.unit + 's have you done?\nEnter a negative number to decrement.',
                input: {
                    hint: '0',
                    allowEmptyInput: false,
                    type: 3,
                    callback: (amount) => {
                        amount = parseFloat(amount)
                        task.countToday += amount
                        task.count += amount
                        task.history[month] += amount
                        this.props.save(this.state.tasks)
                    }
                },
                positiveText: 'OK',
                negativeText: 'Cancel'
            };

            var dialog = new DialogAndroid();
            dialog.set(options);
            dialog.show();
        }
        else {
            task.count += task.checked ? 1 : -1
            task.history[month] += task.checked ? 1 : -1
            this.props.save(this.state.tasks)
        }
        
    }
    
    action(taskIndex, a) {
        if(a == '0') { // Delete
            this.state.tasks.splice(taskIndex, 1)
            this.props.save(this.state.tasks)
        }
    }
    
    checkDate(task) {
        if(task.checked && task.lastChecked != today) {
            task.countToday = 0
            task.checked = false
            this.props.save(this.state.tasks)
        }
    }
    
    plural(count, unit) {
        if(!unit) return count
        return count + ' ' + unit + (count == 1 ? '' : 's') 
    }
    
    render() {
        var items = [];
        this.state.tasks.forEach(function(task, i) {
            this.checkDate(task)
            items.push(
                <ListItem key={i} onLongPress ={() => ActionSheet.show({options: ['Delete'], title: task.title }, (a) => this.action(i, a))} onPress={() => this.toggle(task)}>
                    <Left>
                        <Body>
                            <Text>{ task.title }</Text>
                            {task.type == 'unlimited' ? (
                                <Text note>{ this.plural(task.countToday, task.unit) } done today!</Text>
                            ) : null}
                            <Text note>{ this.plural(task.history.hasOwnProperty(month) ? task.history[month] : 0, task.unit) } done this month!</Text>
                        </Body>
                    </Left>
                    <Right>
                    
                        {task.type == 'unlimited' ? (
                            <Button onPress={() => this.toggle(task)}><Icon name="ios-add" /></Button>
                        ) : (
                            <CheckBox style={{marginRight: 28}} checked={task.checked} onPress={() => this.toggle(task)}/>
                        )}
                    </Right>
                </ListItem>
            );
        }.bind(this));
        
        var itemsHistory = [];
        this.state.tasks.forEach(function(task, i) {
            Object.keys(task.history).forEach(function(key) {
                if(key == month) return;
                itemsHistory.push(
                    <ListItem key={i}>
                        <Left>
                            <Body>
                                <Text>{ task.title }</Text>
                                <Text note>{ task.history[key] } done!</Text>
                            </Body>
                        </Left>
                        <Right>
                            <Text>{ key }</Text>
                        </Right>
                    </ListItem>
                );
            })
            
        }.bind(this));
        
        return (
            <Container>
                <Content>
                    <List>
                        <ListItem itemDivider>
                           <Text>Today</Text>
                           <Right>
                                <Text>{ today }</Text>
                           </Right>
                        </ListItem>
                        { items }
                        <ListItem itemDivider>
                           <Text>History</Text>
                        </ListItem>
                        { itemsHistory }
                    </List>
                </Content>
                <Footer >
                    <FooterTab>
                        <Button full onPress={() => Actions.addNewTask({cb: (task) => this.addNewTask(task)})}>
                            <Icon name="ios-add" /><Text>Add New</Text>
                        </Button>
                    </FooterTab>
                </Footer>
            </Container>
        );
    }
}
