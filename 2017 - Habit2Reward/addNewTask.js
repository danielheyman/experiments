import React, { Component } from 'react';
import { ToastAndroid, View, Picker } from 'react-native';
import { H3, InputGroup, Grid, Col, Form, Item, Label, Input, Text, Card, CardItem, Container, Header, Title, Content, Footer, FooterTab, Button, Left, Right, Body, Icon } from 'native-base';
import { Actions } from 'react-native-router-flux';

export default class AddNewTask extends Component {
    
    state = {
        name: '',
        countToReward: '',
        unit: '',
        reward: '',
        type: 'once'
    }
    
    save(state) {
    
        if(state.name == '') {
            ToastAndroid.show('Enter a name', ToastAndroid.SHORT)
        }
        else if(state.type == 'unlimited' && state.unit == '') {
            ToastAndroid.show('Enter a unit', ToastAndroid.SHORT)
        }
        else if(state.countToReward == '') {
            ToastAndroid.show('Enter a count', ToastAndroid.SHORT)
        }
        else if(state.reward == '') {
            ToastAndroid.show('Enter a reward', ToastAndroid.SHORT)
        }
        else {
            Actions.pop();
            this.props.cb({
                title: state.name,
                count: 0,
                used: 0,
                type: state.type,
                unit: state.type == 'unlimited' ? state.unit : null,
                countToday: 0,
                countToReward: parseFloat(state.countToReward),
                reward: state.reward,
                checked: false,
                history: {}
             });
        }
    }
    
    render() {
        return (
            <Container>
                <Header>
                    <Left>
                        <Button transparent onPress={Actions.pop}>
                            <Icon name='arrow-back' />
                        </Button>
                    </Left>
                    <Body>
                        <Title>Add New Task</Title>
                    </Body>
                    <Right />
                </Header>
                <Content>
                    <Form>
                        <Content padder>
                            <View style={{flex: 1, flexDirection: 'row'}}>
                                <View>
                                    <Text style={{marginTop: 14}}>Title: </Text>
                                </View>
                                <View style={{flex: 1}}>
                                    <InputGroup underline>
                                        <Input placeholder="Read" style={{alignSelf: 'stretch'}} value={this.state.name} onChangeText={(v) => this.setState({name: v})} />      
                                    </InputGroup>
                                </View>
                            </View>
                            <View style={{flex: 1, flexDirection: 'row', marginTop: 20}}>
                                <View>
                                    <Text style={{marginTop: 14}}>Type: </Text>
                                </View>
                                <View style={{flex: 1}}>
                                    <Picker
                                          selectedValue={this.state.type}
                                          onValueChange={(t) => this.setState({type: t})}
                                          >
                                          <Picker.Item label="Once a Day" value="once" />
                                          <Picker.Item label="Unlimited per Day" value="unlimited" />
                                    </Picker>
                                </View>
                            </View>
                            {this.state.type == 'unlimited' ? (
                                <View style={{marginTop: 10, flex: 1, flexDirection: 'row'}}>
                                    <View style={{marginTop: 14}}>
                                        <Text>A singular count is a: </Text>
                                    </View>
                                    <View style={{flex: 1}}>
                                        <InputGroup underline>
                                            <Input placeholder="minute" value={this.state.unit} onChangeText={(v) => this.setState({unit: v})}/>
                                        </InputGroup>
                                    </View>
                                </View>
                            ) : null}
                            <View style={{marginTop: 10, flex: 1, flexDirection: 'row'}}>
                                <View>
                                    <Text style={{marginTop: 14}}>{ this.state.name ? this.state.name : 'Do your task'}</Text>
                                </View>
                                <View>
                                    <InputGroup style={{width: 50}} underline>
                                        <Input placeholder="0" value={this.state.countToReward} keyboardType="numeric" onChangeText={(v) => this.setState({countToReward: v})}/>
                                    </InputGroup>
                                </View>
                                <View>
                                    <Text style={{marginTop: 14}}>{this.state.unit ? this.state.unit + 's' : 'times'} to get rewarded:</Text>
                                </View>
                            </View>
                            <Item>
                                <Input placeholder="order takeout" value={this.state.reward} onChangeText={(v) => this.setState({reward: v})} />
                            </Item>
                            <Button style={{marginTop: 20}} full onPress={() => this.save(this.state)}>
                                <Icon name="ios-checkmark" /><Text>Save</Text>
                            </Button>
                        </Content>
                    </Form>
                </Content>
            </Container>
        );
    }
}
