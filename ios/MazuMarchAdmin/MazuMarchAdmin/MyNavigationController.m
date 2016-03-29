//
//  MyNavigationController.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/28.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "MyNavigationController.h"

@interface MyNavigationController ()

@end

@implementation MyNavigationController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    self.navigationBar.barTintColor = [UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00];
    [self.navigationBar setTitleTextAttributes:@{NSForegroundColorAttributeName:[UIColor colorWithRed:1 green:1 blue:1 alpha:1]}];
    [self.navigationBar setTranslucent:NO];
    [self.navigationBar setBarStyle:UIBarStyleBlack];
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
