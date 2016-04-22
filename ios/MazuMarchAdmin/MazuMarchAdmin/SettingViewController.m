//
//  SettingViewController.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/4/22.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "SettingViewController.h"

@interface SettingViewController ()

@end

@implementation SettingViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    [self.view.layer setBackgroundColor:[UIColor colorWithRed:1 green:1 blue:1 alpha:1].CGColor];
    
    [self.navigationController setNavigationBarHidden:NO];
    self.navigationController.navigationBar.tintColor = [UIColor colorWithRed:1 green:1 blue:1 alpha:1];
    
    int s = 10;
    
    self.scrollView = [UIScrollView new];
    [self.scrollView setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.view addSubview:self.scrollView];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTop multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeLeading relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeLeading multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeTrailing relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTrailing multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeBottom multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeCenterX relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeCenterX multiplier:1.0 constant:0.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.scrollView attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeCenterY multiplier:1.0 constant:0.0]];
    
    self.pathTitleLabel = [UILabel new];
    [self.pathTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.pathTitleLabel setText:@"路徑顯示："];
    [self.pathTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.pathTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.pathTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeTop multiplier:1 constant:20]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.pathTitleLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.scrollView attribute:NSLayoutAttributeLeft multiplier:1 constant:10]];
    
    
    self.versionTitleLabel = [UILabel new];
    [self.versionTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.versionTitleLabel setText:@"ＪＳ版本："];
    [self.versionTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.versionTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.versionTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.pathTitleLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:s]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.versionTitleLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.pathTitleLabel attribute:NSLayoutAttributeLeft multiplier:1 constant:0]];
    
    self.crontabTitleLabel = [UILabel new];
    [self.crontabTitleLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.crontabTitleLabel setText:@"排程開關："];
    [self.crontabTitleLabel setTextColor:[UIColor colorWithRed:0.50 green:0.50 blue:0.52 alpha:1.00]];
    
    [self.scrollView addSubview: self.crontabTitleLabel];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.crontabTitleLabel attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.versionTitleLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:s]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.crontabTitleLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.versionTitleLabel attribute:NSLayoutAttributeLeft multiplier:1 constant:0]];
    

    self.pathSegmentedControl = [[UISegmentedControl alloc] initWithItems:@[@"十九下午", @"十九晚間", @"十九晚間"]];
    [self.pathSegmentedControl setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.pathSegmentedControl addTarget:self action:@selector(chooseOne:) forControlEvents:UIControlEventValueChanged];
    [self.pathSegmentedControl setSelectedSegmentIndex:0];

    [self.view addSubview:self.pathSegmentedControl];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.pathSegmentedControl attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.pathTitleLabel attribute:NSLayoutAttributeCenterY multiplier:1 constant:0]];
    [self.scrollView addConstraint:[NSLayoutConstraint constraintWithItem:self.pathSegmentedControl attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.pathTitleLabel attribute:NSLayoutAttributeRight multiplier:1 constant:2]];
    
    

    

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
