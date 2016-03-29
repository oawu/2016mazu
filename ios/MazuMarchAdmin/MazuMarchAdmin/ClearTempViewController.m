//
//  ClearTempViewController.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/29.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "ClearTempViewController.h"

@interface ClearTempViewController ()

@end

@implementation ClearTempViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    [self.view.layer setBackgroundColor:[UIColor colorWithRed:1 green:1 blue:1 alpha:1].CGColor];
    
    [self.navigationController setNavigationBarHidden:NO];
    self.navigationController.navigationBar.tintColor = [UIColor colorWithRed:1 green:1 blue:1 alpha:1];

    
    
    
    self.button = [UIButton new];
    [self.button setTitle:@"點擊清除" forState:UIControlStateNormal];
    [self.button setTitle:@"清除中.." forState:UIControlStateDisabled];
    [self.button setTitleColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00] forState:UIControlStateNormal];
    [self.button setTitleColor:[UIColor colorWithRed:0.67 green:0.19 blue:0.18 alpha:1.00] forState:UIControlStateHighlighted];
    [self.button setTitleColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:.60] forState:UIControlStateDisabled];
    [self.button setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.button addTarget:self action:@selector(clean:) forControlEvents:UIControlEventTouchUpInside];
    
    [self.view addSubview:self.button];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.button attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.topLayoutGuide attribute:NSLayoutAttributeBottom multiplier:1 constant:20]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.button attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeLeft multiplier:1 constant:20.0]];

    self.label = [UILabel new];
    [self.label setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.label setText:@"所有 Temp json 檔案！"];
    
    [self.view addSubview:self.label];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.label attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.button attribute:NSLayoutAttributeCenterY multiplier:1 constant:0.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.label attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.button attribute:NSLayoutAttributeRight multiplier:1 constant:10.0]];
    
    self.result = [UILabel new];
    [self.result setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.result setText:@""];
    
    [self.view addSubview:self.result];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.result attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.button attribute:NSLayoutAttributeLeft multiplier:1 constant:0.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.result attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.button attribute:NSLayoutAttributeBottom multiplier:1 constant:10.0]];
    
}

- (void)clean:(UIButton *)sender {
    [sender setEnabled:NO];
    
    

}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
