//
//  MarchViewController.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/29.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "MarchViewController.h"

@interface MarchViewController ()

@end

@implementation MarchViewController

- (void)viewDidLoad {
    [super viewDidLoad];

    [self.view.layer setBackgroundColor:[UIColor colorWithRed:1 green:1 blue:1 alpha:1].CGColor];

    [self.navigationController setNavigationBarHidden:NO];
    self.navigationController.navigationBar.tintColor = [UIColor colorWithRed:1 green:1 blue:1 alpha:1];
    
    [self initUI];
}

-(void)initUI {
    self.label = [UILabel new];
    [self.label setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.label setText:@"選擇觀看哪時段的媽祖位置："];
    
    
    [self.view addSubview:self.label];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.label attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.topLayoutGuide attribute:NSLayoutAttributeBottom multiplier:1 constant:20.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.label attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeLeft multiplier:1 constant:10.0]];
    
    self.segmentedControl = [[UISegmentedControl alloc] initWithItems:@[@"十九早", @"十九中", @"十九晚", @"二十早", @"二十中", @"二十晚"]];
    [self.segmentedControl setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.segmentedControl addTarget:self action:@selector(chooseOne:) forControlEvents:UIControlEventValueChanged];
    [self.segmentedControl setSelectedSegmentIndex:[[USER_DEFAULTS objectForKey:@"march_id"] integerValue] - 1];
    
    [self.view addSubview:self.segmentedControl];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.segmentedControl attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.label attribute:NSLayoutAttributeBottom multiplier:1 constant:10.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.segmentedControl attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.label attribute:NSLayoutAttributeLeft multiplier:1 constant:0.0]];
}

- (void)chooseOne:(id)sender {
    [USER_DEFAULTS setValue:[NSString stringWithFormat:@"%d", (int)[sender selectedSegmentIndex] + 1] forKey:@"march_id"];
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
