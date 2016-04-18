//
//  LockViewController.m
//  MazuMarch
//
//  Created by OA Wu on 2016/4/18.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "LockViewController.h"

@interface LockViewController ()

@end

@implementation LockViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    
    [self.view.layer setBackgroundColor:[UIColor colorWithRed:1 green:1 blue:1 alpha:1].CGColor];
    [self.view.layer setOpacity:.5];
    

    // Do any additional setup after loading the view.
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
